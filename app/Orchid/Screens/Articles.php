<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\article as art;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Repository;
use Illuminate\Support\Str;

class Articles extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Articles';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            "articles" => art::all(),
            'table'   => art::all(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [Button::make('Go print')->method('print'),];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [Layout::table('table', [
            TD::make('id', 'id')
                ->width('100')
                ->render(function ($model) {
                    // Please use view('path')
                    return $model->id;
                }),
            TD::make('description', 'description')
                ->width('450')
                ->render(function ($model) {
                    return Str::limit($model->description, 200);
                }),
                TD::make('url', 'Url')
                ->render(function ($model) {
                    return "<a href='{$model->url}'>{$model->url}</a>";
                }),

                TD::make('author', 'Author')
                ->width('200')
                ->render(function ($model) {
                    return $model->author;
                }),




                TD::make('datetime', 'Date time')
                
                ->render(function ($model) {
                    return $model->datetime;
                }),
        ]),];
    }
}
