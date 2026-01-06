<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'costume_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'qr_code',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the rental.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the costume that is rented.
     */
    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }

    /**
     * Calculate number of days for the rental
     */
    public function getDaysCount(): int
    {
        return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
    }

    /**
     * Calculate total price based on costume price and rental days
     */
    public function calculateTotalPrice(): float
    {
        return $this->costume->price_per_day * $this->getDaysCount();
    }
}
