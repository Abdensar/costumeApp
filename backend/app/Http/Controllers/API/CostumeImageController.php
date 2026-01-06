<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostumeImageStoreRequest;
use App\Models\Costume;
use App\Models\CostumeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CostumeImageController extends Controller
{
    public function index(Costume $costume)
    {
        return response()->json(['success' => true, 'data' => $costume->images()->orderBy('position')->get()]);
    }

    public function store(CostumeImageStoreRequest $request, Costume $costume)
    {
        // Admin middleware should protect this route
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/costumes');
            $url = Storage::url($path);
        } else {
            $url = $request->input('image_url');
        }

        $image = CostumeImage::create([
            'costume_id' => $costume->id,
            'image_url' => $url,
            'position' => $request->input('position', 0),
        ]);

        return response()->json(['success' => true, 'data' => $image], 201);
    }
}
