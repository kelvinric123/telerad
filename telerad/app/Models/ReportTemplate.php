<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportTemplate extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'modality',
        'section',
        'content',
        'is_default',
        'user_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];
    
    /**
     * Get the user that created the template.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include templates for a specific modality.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $modality
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByModality($query, $modality)
    {
        return $query->where('modality', $modality)->orWhereNull('modality');
    }
    
    /**
     * Scope a query to only include templates for a specific section.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $section
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }
}
