<?php

namespace App\Orchid\Screens;

use App\Models\log;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Illuminate\Support\Str;

class LogListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Logs';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return ['table'   => log::filters()->defaultSort('id')->paginate(),];
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
            TD::make('id', 'Id')
                ->width(50)
                ->sort()
                ->align(TD::ALIGN_CENTER)
                ->render(function ($model) {
                    return $model->id;
                }),
            TD::make('request_method', 'Request method')
                ->width(200)
                ->align(TD::ALIGN_CENTER)
                ->render(function ($model) {
                    return $model->request_method;
                }),
            TD::make('request_url', 'Request url')
                ->width(200)
                ->render(function ($model) {
                    return $model->request_url;
                }),

            TD::make('response_http_code', 'Response HTTP code')
                ->render(function ($model) {
                    return $model->response_body;
                }),
            TD::make('response_body', 'Response Body')
                ->align(TD::ALIGN_CENTER)
                ->render(function ($model) {
                    return $model->response_body;
                }),
            TD::make('created_at', 'Created at')
                ->sort()
                ->align(TD::ALIGN_CENTER)
                ->render(function ($model) {
                    return $model->created_at;
                }),
        ])];
    }
}
