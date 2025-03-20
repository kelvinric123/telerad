<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Series extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'study_id',
        'series_uid',
        'series_number',
        'modality',
        'series_description',
        'body_part_examined',
        'number_of_instances',
        'orthancId',
        'dicom_tags',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'series_number' => 'integer',
        'number_of_instances' => 'integer',
        'dicom_tags' => 'json',
    ];
    
    /**
     * Get the study that owns the series.
     */
    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }
    
    /**
     * Get the patient via the study.
     */
    public function patient()
    {
        return $this->study->patient;
    }
}
