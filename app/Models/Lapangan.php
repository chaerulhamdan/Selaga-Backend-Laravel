<?php

namespace App\Models;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lapangan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lapangan';

    protected $fillable = [
        'venue',
        'nameLapangan', 
        'days',
        'hour', 
        'venueId',
    ];

    public function venue() : BelongsTo {
        return $this->belongsTo(Venue::class, 'venueId', 'id');
    }

    public function timetable() : HasMany
    {
        return $this->hasMany(Timetable::class, 'lapanganId', 'id');
    }
}
