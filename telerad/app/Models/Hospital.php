<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'fax',
        'email',
        'website',
        'logo_path',
        'tax_id',
        'registration_number',
    ];

    /**
     * Get the users associated with the hospital.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
} 