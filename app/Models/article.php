<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\enclosure;

class article extends Model
{
    use HasFactory;
    public function images()
    {
        return $this->hasMany(enclosure::class,'article_id', 'id');
    }
}
