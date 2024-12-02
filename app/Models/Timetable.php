<?php

namespace App\Models;

use App\Models\Lapangan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'timetable';

    protected $fillable = [
        'nameVenue', 
        'nameLapangan', 
        'days',
        'availableHour',
        'unavailableHour',
        'lapanganId',
    ];

    public function lapangan() : BelongsTo {
        return $this->belongsTo(Lapangan::class, 'lapanganId', 'id');
    }

    public function bookings() : HasMany
    {
        return $this->hasMany(Booking::class, 'bookingId', 'id');
    }
}
