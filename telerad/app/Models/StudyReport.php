<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyReport extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'study_id',
        'user_id',
        'findings',
        'impression',
        'recommendations',
        'status',
        'finalized_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'finalized_at' => 'datetime',
    ];
    
    /**
     * Get the study this report belongs to.
     */
    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }
    
    /**
     * Get the radiologist (user) who created this report.
     */
    public function radiologist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Check if the report is finalized
     */
    public function isFinalized(): bool
    {
        return $this->status === 'final' || $this->status === 'amended';
    }
}
