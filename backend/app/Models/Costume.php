<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costume extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'size',
        'brand',
        'price_per_day',
        'images',
        'featured_image_url',
        'is_active',
        'available',
    ];

    protected $casts = [
        'images' => 'array',
        'available' => 'boolean',
        'price_per_day' => 'decimal:2',
    ];

    /**
     * Get the category that owns the costume.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function costumeImages()
    {
        return $this->hasMany(CostumeImage::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    /**
     * Get the rentals for the costume.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Check if costume is available for given date range
     */
    public function isAvailableForDates($startDate, $endDate, $excludeRentalId = null)
    {
        $query = $this->rentals()
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'returned')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeRentalId) {
            $query->where('id', '!=', $excludeRentalId);
        }

        return $query->count() === 0;
    }
}
