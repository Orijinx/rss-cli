<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\enclosure;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class article extends Model
{
    use HasFactory, Filterable,AsSource;
    protected $fillable = ['title','url','description','datetime','author'];
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
