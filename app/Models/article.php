<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\enclosure;
use Orchid\Filters\Filterable;

class article extends Model
{
    use HasFactory, Filterable;
    protected $allowedSorts = [
        'title',
        'datetime',
        'id'
    ];
    public function images()
    {
        return $this->hasMany(enclosure::class, 'article_id', 'id');
    }
}
