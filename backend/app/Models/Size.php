<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['costume_id', 'size_label', 'quantity_available'];

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }
}
