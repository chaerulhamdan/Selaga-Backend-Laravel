<?php

namespace App\Models;

use App\Models\Mitra;
use App\Models\Lapangan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'venue';

    protected $fillable = [
        'venue',
        'nameVenue', 
        'lokasiVenue',
        'descVenue', 
        'fasilitasVenue',
        'price',
        'rating',
        'image',
        'mitraId',
    ];

    public function owner() : BelongsTo {
        return $this->belongsTo(Mitra::class, 'mitraId', 'id');
    }

    public function lapangans() : HasMany {
        return $this->hasMany(Lapangan::class, 'venueId', 'id');
    }
}
