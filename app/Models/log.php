<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class log extends Model
{
    use HasFactory,Filterable,AsSource;
    protected $fillable = ['request_method','request_url','response_http_code','response_body'];
    protected $allowedSorts = [
        'created_at',
        'id'
    ];
}
