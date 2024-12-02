<?php

namespace App\Models;

use App\Models\User;
use App\Models\Timetable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'booking';

    protected $fillable = [
        'orderName', 
        'date', 
        'hours',
        'payment',
        'orderId',
        'bookingId',
        'image',
        'confirmation',
        'ratingStatus'
    ];

    public function order() : BelongsTo {
        return $this->belongsTo(User::class, 'orderId', 'id');
    }

    public function timetable() : BelongsTo {
        return $this->belongsTo(Timetable::class, 'bookingId', 'id');
    }
}
