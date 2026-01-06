<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RentalRequest;
use App\Http\Resources\RentalResource;
use App\Models\Costume;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RentalController extends Controller
{
    /**
     * Get all rentals (admin only)
     */
    public function index()
    {
        $rentals = Rental::with(['user', 'costume.category'])
            ->orderBy('created_at', 'desc')
            ->get();

        return RentalResource::collection($rentals);
    }

    /**
     * Get customer's own rentals
     */
    public function myRentals(Request $request)
    {
        $rentals = Rental::with(['costume.category'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return RentalResource::collection($rentals);
    }

    /**
     * Create a new rental (customer booking)
     */
    public function store(RentalRequest $request)
    {
        $costume = Costume::findOrFail($request->costume_id);

        // Check if costume is available for the date range
        if (!$costume->isAvailableForDates($request->start_date, $request->end_date)) {
            return response()->json([
                'message' => 'Costume is not available for the selected dates',
            ], 422);
        }

        // Calculate total price
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $costume->price_per_day * $days;

        // Create rental
        $rental = Rental::create([
            'user_id' => $request->user()->id,
            'costume_id' => $request->costume_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        $rental->load(['costume.category']);

        return response()->json([
            'message' => 'Rental request created successfully',
            'data' => new RentalResource($rental),
        ], 201);
    }

    /**
     * Update rental status (admin only)
     */
    public function updateStatus(Request $request, Rental $rental)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,returned'],
        ]);

        $rental->status = $request->status;

        // Generate QR code when rental is confirmed
        if ($request->status === 'confirmed' && !$rental->qr_code) {
            $qrCodeData = 'RENTAL-' . $rental->id . '-' . Str::random(10);
            $rental->qr_code = $qrCodeData;

            // Mark costume as unavailable
            $rental->costume->update(['available' => false]);
        }

        // Mark costume as available when returned
        if ($request->status === 'returned') {
            $rental->costume->update(['available' => true]);
        }

        // Mark costume as available when cancelled
        if ($request->status === 'cancelled') {
            $rental->costume->update(['available' => true]);
        }

        $rental->save();
        $rental->load(['user', 'costume.category']);

        return response()->json([
            'message' => 'Rental status updated successfully',
            'data' => new RentalResource($rental),
        ], 200);
    }

    /**
     * Get rental statistics (admin only)
     */
    public function statistics()
    {
        $totalRentals = Rental::count();
        $pendingRentals = Rental::where('status', 'pending')->count();
        $confirmedRentals = Rental::where('status', 'confirmed')->count();
        $completedRentals = Rental::where('status', 'returned')->count();
        $totalRevenue = Rental::whereIn('status', ['confirmed', 'returned'])->sum('total_price');

        // Revenue per month (last 12 months)
        $monthlyRevenue = Rental::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as revenue')
            ->whereIn('status', ['confirmed', 'returned'])
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json([
            'total_rentals' => $totalRentals,
            'pending_rentals' => $pendingRentals,
            'confirmed_rentals' => $confirmedRentals,
            'completed_rentals' => $completedRentals,
            'total_revenue' => (float) $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
        ], 200);
    }
}
