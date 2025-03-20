<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Study;
use App\Models\Series;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrthancSyncService
{
    protected $client;
    protected $orthancUrl;
    
    public function __construct()
    {
        $this->orthancUrl = env('ORTHANC_URL', 'http://localhost:8042');
        
        // Initialize Guzzle client
        $this->client = new Client([
            'base_uri' => $this->orthancUrl,
            'auth' => [
                env('ORTHANC_USERNAME', 'orthanc'),
                env('ORTHANC_PASSWORD', 'orthanc')
            ],
            'timeout' => 30.0,
            'connect_timeout' => 5.0,
            'http_errors' => false
        ]);
    }
    
    /**
     * Sync all patients, studies, and series from Orthanc to the local database
     */
    public function syncAll()
    {
        try {
            // Get all patients from Orthanc
            $response = $this->client->get('/patients');
            
            if ($response->getStatusCode() != 200) {
                Log::error("Failed to get patients from Orthanc: " . $response->getStatusCode());
                return false;
            }
            
            $patientIds = json_decode($response->getBody(), true);
            
            Log::info("Found " . count($patientIds) . " patients in Orthanc");
            
            foreach ($patientIds as $patientId) {
                $this->syncPatient($patientId);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Error in syncAll: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Sync a specific patient and all their studies
     */
    public function syncPatient($orthancPatientId)
    {
        try {
            $response = $this->client->get('/patients/' . $orthancPatientId);
            
            if ($response->getStatusCode() != 200) {
                Log::error("Failed to get patient details: " . $response->getStatusCode());
                return false;
            }
            
            $patientData = json_decode($response->getBody(), true);
            
            // Create or update patient record
            $patient = Patient::updateOrCreate(
                ['orthancId' => $orthancPatientId],
                [
                    'patient_id' => $patientData['MainDicomTags']['PatientID'] ?? null,
                    'name' => $patientData['MainDicomTags']['PatientName'] ?? null,
                    'birth_date' => $this->formatDicomDate($patientData['MainDicomTags']['PatientBirthDate'] ?? null),
                    'sex' => $patientData['MainDicomTags']['PatientSex'] ?? null,
                    'dicom_tags' => json_encode($patientData['MainDicomTags'])
                ]
            );
            
            // Sync all studies for this patient
            foreach ($patientData['Studies'] as $studyId) {
                $this->syncStudy($studyId, $patient->id);
            }
            
            return $patient;
        } catch (\Exception $e) {
            Log::error("Error in syncPatient: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Sync a specific study and all its series
     */
    public function syncStudy($orthancStudyId, $patientId)
    {
        try {
            $response = $this->client->get('/studies/' . $orthancStudyId);
            
            if ($response->getStatusCode() != 200) {
                Log::error("Failed to get study details: " . $response->getStatusCode());
                return false;
            }
            
            $studyData = json_decode($response->getBody(), true);
            
            // Create or update study record
            $study = Study::updateOrCreate(
                ['orthancId' => $orthancStudyId],
                [
                    'patient_id' => $patientId,
                    'study_uid' => $studyData['MainDicomTags']['StudyInstanceUID'] ?? null,
                    'accession_number' => $studyData['MainDicomTags']['AccessionNumber'] ?? null,
                    'study_id' => $studyData['MainDicomTags']['StudyID'] ?? null,
                    'study_description' => $studyData['MainDicomTags']['StudyDescription'] ?? null,
                    'study_date' => $this->formatDicomDate($studyData['MainDicomTags']['StudyDate'] ?? null),
                    'study_time' => $this->formatDicomTime($studyData['MainDicomTags']['StudyTime'] ?? null),
                    'referring_physician' => $studyData['MainDicomTags']['ReferringPhysicianName'] ?? null,
                    'modalities' => isset($studyData['MainDicomTags']['ModalitiesInStudy']) 
                        ? json_encode(explode('\\', $studyData['MainDicomTags']['ModalitiesInStudy']))
                        : json_encode($studyData['Modalities'] ?? []),
                    'dicom_tags' => json_encode($studyData['MainDicomTags']),
                    'is_fetched' => true
                ]
            );
            
            // Sync all series for this study
            foreach ($studyData['Series'] as $seriesId) {
                $this->syncSeries($seriesId, $study->id);
            }
            
            return $study;
        } catch (\Exception $e) {
            Log::error("Error in syncStudy: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Sync a specific series
     */
    public function syncSeries($orthancSeriesId, $studyId)
    {
        try {
            $response = $this->client->get('/series/' . $orthancSeriesId);
            
            if ($response->getStatusCode() != 200) {
                Log::error("Failed to get series details: " . $response->getStatusCode());
                return false;
            }
            
            $seriesData = json_decode($response->getBody(), true);
            
            // Create or update series record
            $series = Series::updateOrCreate(
                ['orthancId' => $orthancSeriesId],
                [
                    'study_id' => $studyId,
                    'series_uid' => $seriesData['MainDicomTags']['SeriesInstanceUID'] ?? null,
                    'series_number' => $seriesData['MainDicomTags']['SeriesNumber'] ?? null,
                    'modality' => $seriesData['MainDicomTags']['Modality'] ?? null,
                    'series_description' => $seriesData['MainDicomTags']['SeriesDescription'] ?? null,
                    'body_part_examined' => $seriesData['MainDicomTags']['BodyPartExamined'] ?? null,
                    'number_of_instances' => count($seriesData['Instances'] ?? []),
                    'dicom_tags' => json_encode($seriesData['MainDicomTags'])
                ]
            );
            
            return $series;
        } catch (\Exception $e) {
            Log::error("Error in syncSeries: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Format DICOM date (YYYYMMDD) to MySQL date format (YYYY-MM-DD)
     */
    private function formatDicomDate($dicomDate)
    {
        if (!$dicomDate) {
            return null;
        }
        
        try {
            return Carbon::createFromFormat('Ymd', $dicomDate)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Format DICOM time (HHMMSS) to MySQL time format (HH:MM:SS)
     */
    private function formatDicomTime($dicomTime)
    {
        if (!$dicomTime) {
            return null;
        }
        
        try {
            // Handle various DICOM time formats
            $dicomTime = substr($dicomTime, 0, 6); // Take only HHMMSS part
            return Carbon::createFromFormat('His', $dicomTime)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
} 