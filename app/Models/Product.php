<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = ['name', 'description', 'price', 'user_id', 'image_path',]; // Asegúrate de incluir user_id

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
