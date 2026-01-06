<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostumeRequest;
use App\Http\Resources\CostumeResource;
use App\Models\Costume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CostumeController extends Controller
{
    /**
     * Display a listing of costumes with filters
     */
    public function index(Request $request)
    {
        $query = Costume::with(['category', 'costumeImages', 'sizes']);

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by size
        if ($request->has('size')) {
            $query->where('size', $request->size);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        // Filter by availability
        if ($request->has('available')) {
            $query->where('available', $request->available);
        }

        $costumes = $query->get();

        return CostumeResource::collection($costumes);
    }

    /**
     * Store a newly created costume
     */
    public function store(CostumeRequest $request)
    {
        $data = $request->validated();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('costumes', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $costume = Costume::create($data);
        $costume->load('category');

        return response()->json([
            'message' => 'Costume created successfully',
            'data' => new CostumeResource($costume),
        ], 201);
    }

    /**
     * Display the specified costume
     */
    public function show(Costume $costume)
    {
        $costume->load(['category', 'costumeImages', 'sizes']);
        return new CostumeResource($costume);
    }

    /**
     * Update the specified costume
     */
    public function update(CostumeRequest $request, Costume $costume)
    {
        $data = $request->validated();

        // Handle new image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($costume->images) {
                foreach ($costume->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('costumes', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $costume->update($data);
        $costume->load('category');

        return response()->json([
            'message' => 'Costume updated successfully',
            'data' => new CostumeResource($costume),
        ], 200);
    }

    /**
     * Remove the specified costume
     */
    public function destroy(Costume $costume)
    {
        // Delete associated images
        if ($costume->images) {
            foreach ($costume->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $costume->delete();

        return response()->json([
            'message' => 'Costume deleted successfully',
        ], 200);
    }
}
