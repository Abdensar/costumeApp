<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SizeStoreRequest;
use App\Models\Costume;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index(Costume $costume)
    {
        return response()->json(['success' => true, 'data' => $costume->sizes()->get()]);
    }

    public function store(SizeStoreRequest $request, Costume $costume)
    {
        $size = Size::create([
            'costume_id' => $costume->id,
            'size_label' => $request->input('size_label'),
            'quantity_available' => $request->input('quantity_available'),
        ]);

        return response()->json(['success' => true, 'data' => $size], 201);
    }
}
