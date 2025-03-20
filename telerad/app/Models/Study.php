<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Study extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'study_uid',
        'accession_number',
        'study_id',
        'study_description',
        'study_date',
        'study_time',
        'referring_physician',
        'orthancId',
        'modalities',
        'dicom_tags',
        'is_fetched',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'study_date' => 'date',
        'study_time' => 'datetime:H:i:s',
        'modalities' => 'json',
        'dicom_tags' => 'json',
        'is_fetched' => 'boolean',
    ];
    
    /**
     * Get the patient that owns the study.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    
    /**
     * Get the series for the study.
     */
    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }
    
    /**
     * Get the reports for the study.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(StudyReport::class);
    }
    
    /**
     * Format modalities array as a comma-separated string
     */
    public function getModalitiesStringAttribute(): string
    {
        $modalities = $this->modalities ?? [];
        
        if (is_string($modalities)) {
            $modalities = json_decode($modalities, true) ?? [];
        }
        
        // If we have a string with backslash separators (ModalitiesInStudy format)
        if (is_array($modalities) && count($modalities) === 1 && strpos($modalities[0], '\\') !== false) {
            $modalities = explode('\\', $modalities[0]);
        }
        
        return implode(', ', $modalities);
    }
    
    /**
     * Get a human-readable description of the modality
     */
    public function getModalityDescriptionAttribute(): string
    {
        $modalities = $this->modalities ?? [];
        
        if (is_string($modalities)) {
            $modalities = json_decode($modalities, true) ?? [];
        }
        
        // If we have a string with backslash separators (ModalitiesInStudy format)
        if (is_array($modalities) && count($modalities) === 1 && strpos($modalities[0], '\\') !== false) {
            $modalities = explode('\\', $modalities[0]);
        }
        
        $descriptions = [];
        foreach ($modalities as $modality) {
            switch ($modality) {
                case 'CT':
                    $descriptions[] = 'Computed Tomography (CT)';
                    break;
                case 'MR':
                    $descriptions[] = 'Magnetic Resonance (MR)';
                    break;
                case 'XA':
                    $descriptions[] = 'X-Ray Angiography (XA)';
                    break;
                case 'CR':
                    $descriptions[] = 'Computed Radiography (CR)';
                    break;
                case 'US':
                    $descriptions[] = 'Ultrasound (US)';
                    break;
                case 'DX':
                    $descriptions[] = 'Digital Radiography (DX)';
                    break;
                case 'MG':
                    $descriptions[] = 'Mammography (MG)';
                    break;
                case 'PT':
                    $descriptions[] = 'Positron Emission Tomography (PET)';
                    break;
                case 'NM':
                    $descriptions[] = 'Nuclear Medicine (NM)';
                    break;
                default:
                    $descriptions[] = $modality;
                    break;
            }
        }
        
        return implode(', ', $descriptions);
    }
}
