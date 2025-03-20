<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'name',
        'mrn',
        'birth_date',
        'sex',
        'address',
        'phone',
        'email',
        'orthancId',
        'dicom_tags',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'dicom_tags' => 'json',
    ];
    
    /**
     * Get the studies for the patient.
     */
    public function studies(): HasMany
    {
        return $this->hasMany(Study::class);
    }
    
    /**
     * Format DICOM patient name (Last^First^Middle) to conventional format
     */
    public function getFormattedNameAttribute(): string
    {
        $name = $this->name ?? '';
        $parts = explode('^', $name);
        
        if (count($parts) > 1) {
            return trim($parts[1] . ' ' . $parts[0]);
        }
        
        return $name;
    }
}
