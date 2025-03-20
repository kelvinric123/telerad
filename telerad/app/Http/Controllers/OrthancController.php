<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OrthancController extends Controller
{
    protected $orthancUrl;
    protected $ohifUrl;
    protected $client;
    protected $syncService;

    public function __construct()
    {
        // Default settings - update these in your .env file
        $this->orthancUrl = config('orthanc.url', env('ORTHANC_URL', 'http://localhost:8042'));
        $this->ohifUrl = config('orthanc.ohif_url', env('OHIF_URL', 'http://localhost:3000'));
        
        // Initialize Guzzle client with timeout and exception handling
        $this->client = new Client([
            'base_uri' => $this->orthancUrl,
            'auth' => [
                config('orthanc.username', env('ORTHANC_USERNAME', 'orthanc')),
                config('orthanc.password', env('ORTHANC_PASSWORD', 'orthanc'))
            ],
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            'http_errors' => false
        ]);
        
        // Get the sync service
        $this->syncService = app(\App\Services\OrthancSyncService::class);
    }

    /**
     * Display a listing of all studies
     */
    public function index(Request $request)
    {
        try {
            // Get all studies from Orthanc
            $response = $this->client->get('/studies');
            
            // Check if the request was successful
            if ($response->getStatusCode() != 200) {
                throw new \Exception("Error connecting to Orthanc server: HTTP " . $response->getStatusCode());
            }
            
            $studyIds = json_decode($response->getBody(), true);
            
            $studies = [];
            foreach ($studyIds as $studyId) {
                try {
                    $studyResponse = $this->client->get('/studies/' . $studyId);
                    if ($studyResponse->getStatusCode() == 200) {
                        $studyData = json_decode($studyResponse->getBody(), true);
                        
                        // Process ModalitiesInStudy if available
                        if (isset($studyData['MainDicomTags']['ModalitiesInStudy'])) {
                            $studyData['ModalitiesInStudy'] = explode('\\', $studyData['MainDicomTags']['ModalitiesInStudy']);
                        } else {
                            // If ModalitiesInStudy is not available, try to gather from series
                            try {
                                $modalitiesSet = [];
                                foreach ($studyData['Series'] ?? [] as $seriesId) {
                                    $seriesResponse = $this->client->get('/series/' . $seriesId);
                                    if ($seriesResponse->getStatusCode() == 200) {
                                        $seriesData = json_decode($seriesResponse->getBody(), true);
                                        $modality = $seriesData['MainDicomTags']['Modality'] ?? null;
                                        if ($modality) {
                                            $modalitiesSet[$modality] = true;
                                        }
                                    }
                                }
                                $studyData['ModalitiesInStudy'] = array_keys($modalitiesSet);
                            } catch (\Exception $e) {
                                \Log::warning("Could not extract modalities from series for study $studyId: " . $e->getMessage());
                            }
                        }
                        
                        $studies[] = $studyData;
                    } else {
                        \Log::warning("Study $studyId returned non-200 status: " . $studyResponse->getStatusCode());
                    }
                } catch (\Exception $e) {
                    // Log error but continue with other studies
                    \Log::error("Error fetching study $studyId: " . $e->getMessage());
                }
            }
            
            // Filter parameters
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $patientName = $request->input('patientName');
            $modality = $request->input('modality');
            
            // Convert HTML date format to DICOM format (YYYYMMDD)
            $startDateDicom = $startDate ? str_replace('-', '', $startDate) : null;
            $endDateDicom = $endDate ? str_replace('-', '', $endDate) : null;
            
            // Apply filters
            $filteredStudies = [];
            foreach ($studies as $study) {
                // Skip if study doesn't match date filter
                if ($startDateDicom && ($study['MainDicomTags']['StudyDate'] ?? '') < $startDateDicom) {
                    continue;
                }
                
                if ($endDateDicom && ($study['MainDicomTags']['StudyDate'] ?? '') > $endDateDicom) {
                    continue;
                }
                
                // Skip if study doesn't match patient name filter
                if ($patientName && !$this->matchesPatientName($study, $patientName)) {
                    continue;
                }
                
                // Skip if study doesn't match modality filter
                if ($modality && !$this->matchesModality($study, $modality)) {
                    continue;
                }
                
                $filteredStudies[] = $study;
            }
            
            return view('orthanc.studies', [
                'studies' => $filteredStudies, 
                'ohifUrl' => $this->ohifUrl,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'patientName' => $patientName,
                'modality' => $modality
            ]);
        } catch (\Exception $e) {
            \Log::error("Orthanc connection error: " . $e->getMessage());
            return view('orthanc.error', [
                'message' => $e->getMessage(),
                'title' => 'Connection Error',
                'suggestion' => 'Please ensure the Orthanc server is running and accessible.'
            ]);
        }
    }

    /**
     * Check if study matches patient name filter
     */
    private function matchesPatientName($study, $searchTerm) {
        $patientName = $study['PatientMainDicomTags']['PatientName'] ?? '';
        return stripos($patientName, $searchTerm) !== false;
    }
    
    /**
     * Check if study matches modality filter
     */
    private function matchesModality($study, $modality) {
        // Check in standard Modalities array
        $studyModalities = $study['Modalities'] ?? [];
        if (in_array($modality, $studyModalities)) {
            return true;
        }
        
        // Check in ModalitiesInStudy if available
        $modalitiesInStudy = $study['ModalitiesInStudy'] ?? [];
        if (in_array($modality, $modalitiesInStudy)) {
            return true;
        }
        
        // Also check in MainDicomTags ModalitiesInStudy directly
        if (isset($study['MainDicomTags']['ModalitiesInStudy'])) {
            $rawModalitiesInStudy = $study['MainDicomTags']['ModalitiesInStudy'];
            $modalitiesArray = explode('\\', $rawModalitiesInStudy);
            if (in_array($modality, $modalitiesArray)) {
                return true;
            }
        }
        
        // As a last resort, check if any series has this modality
        foreach ($study['Series'] ?? [] as $seriesId) {
            try {
                $seriesResponse = $this->client->get('/series/' . $seriesId);
                if ($seriesResponse->getStatusCode() == 200) {
                    $seriesData = json_decode($seriesResponse->getBody(), true);
                    if (isset($seriesData['MainDicomTags']['Modality']) && $seriesData['MainDicomTags']['Modality'] === $modality) {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                // Just continue to next series if there's an error
                continue;
            }
        }
        
        return false;
    }

    /**
     * Get details for a specific study
     */
    public function show($studyId)
    {
        try {
            $response = $this->client->get('/studies/' . $studyId);
            $study = json_decode($response->getBody(), true);
            
            // Fetch details for each series
            $seriesDetails = [];
            foreach ($study['Series'] ?? [] as $seriesId) {
                try {
                    $seriesResponse = $this->client->get('/series/' . $seriesId);
                    if ($seriesResponse->getStatusCode() == 200) {
                        $seriesData = json_decode($seriesResponse->getBody(), true);
                        $seriesDetails[$seriesId] = $seriesData;
                    } else {
                        \Log::warning("Series $seriesId returned non-200 status: " . $seriesResponse->getStatusCode());
                    }
                } catch (\Exception $e) {
                    \Log::error("Error fetching series $seriesId: " . $e->getMessage());
                }
            }
            
            // Extract ModalitiesInStudy if available
            $modalitiesInStudy = [];
            if (isset($study['MainDicomTags']['ModalitiesInStudy'])) {
                $modalitiesInStudy = explode('\\', $study['MainDicomTags']['ModalitiesInStudy']);
            }
            
            return view('orthanc.study_details', [
                'study' => $study, 
                'seriesDetails' => $seriesDetails,
                'modalitiesInStudy' => $modalitiesInStudy,
                'ohifUrl' => $this->ohifUrl
            ]);
        } catch (\Exception $e) {
            return view('orthanc.error', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Generate OHIF viewer URL for a study
     */
    public function launchOhif($studyId)
    {
        try {
            // Get study details to retrieve the StudyInstanceUID
            $response = $this->client->get('/studies/' . $studyId);
            $study = json_decode($response->getBody(), true);
            
            // Build OHIF viewer URL with StudyInstanceUIDs parameter
            $studyInstanceUID = $study['MainDicomTags']['StudyInstanceUID'] ?? $studyId;
            $ohifViewerUrl = $this->ohifUrl . '/viewer?StudyInstanceUIDs=' . $studyInstanceUID;
            
            // Redirect to OHIF viewer
            return redirect($ohifViewerUrl);
        } catch (\Exception $e) {
            return view('orthanc.error', ['message' => $e->getMessage()]);
        }
    }
}
