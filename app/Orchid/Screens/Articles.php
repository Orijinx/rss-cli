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
            'table'   => art::filters()->defaultSort('id')->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
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
                ->width('150')
                ->sort()
                ->render(function ($model) {
                    // Please use view('path')
                    return $model->id;
                }),
            // TD::make('images', 'Image')
            //     ->width('100')
            //     ->render(function ($model) {
            //         $url = $model->images()->first();
            //         if (!empty($url)) {
            //             return "<img  width='100' src='{$url->img_url}' alt='Не найдено'>";
            //         } else {
            //             return "-";
            //         }
            //     }),
            TD::make('title', 'Title')
                ->width('200')
                ->sort()
                ->align(TD::ALIGN_CENTER)
                ->render(function ($model) {
                    return Str::limit($model->description, 200);
                }),
            TD::make('description', 'Description')
                ->width('250')
                ->align(TD::ALIGN_CENTER)
                ->render(function ($model) {
                    return Str::limit($model->description, 200);
                }),
            TD::make('url', 'Url')
                ->width('100')
                ->render(function ($model) {
                    return "<a href='{$model->url}'>{$model->url}</a>";
                }),

            TD::make('author', 'Author')
                ->width('50')
                ->render(function ($model) {
                    return $model->author;
                }),
            TD::make('datetime', 'Date time')
                ->width('100')
                ->sort()
                ->align(TD::ALIGN_CENTER)
                ->render(function ($model) {
                    return $model->datetime;
                }),
            TD::make()
                ->width('50')
                ->render(function ($model) {
                    return Button::make()->method("remove")->icon("trash")->confirm(__('Are you sure you want to delete the article?'))
                        ->parameters([
                            'id' => $model->id,
                        ]);
                }),
        ]),];
    }
}
