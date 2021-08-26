<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\article;
use App\Models\enclosure;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Quill;
use Illuminate\Http\Request;
use Orchid\Alert\Alert;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Layout as ScreenLayout;

class ArticlesEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Creating a new article';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'RSS RBK Articles';

    /**
     * @var bool
     */
    public $exists = false;


    /**
     * Query data.
     *
     * @return array
     */
    public function query(article $model): array
    {
        $this->exists = $model->exists;

        if ($this->exists) {
            $this->name = 'Edit Article';
        }

        return [
            'model' => $model
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create Article')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::columns([
                Layout::rows([
                    Input::make('model.title')
                        ->title('Title')
                        ->placeholder('Attractive but mysterious title'),

                    TextArea::make('model.description')
                        ->title('Description')
                        ->rows(3)
                        ->placeholder('Brief description for preview'),
                    TextArea::make('model.url')
                        ->title('Url')
                        ->rows(1)
                        ->placeholder('Brief description for preview'),
                ]),
                Layout::rows([
                    TextArea::make('model.author')
                        ->title('Author')
                        ->rows(3)
                        ->maxlength(200),
                    Relation::make('model.images')
                        ->title('Images')
                        ->multiple()
                        ->fromModel(enclosure::class, 'img_url'),
                    DateTimer::make('model.datetime')
                        ->title('Date time'),
                ]),



            ])
        ];
    }
    public function createOrUpdate(article $model, Request $request)
    {
        $model->fill($request->get('model'))->save();


        return back();
    }

    /**
     * @param Article $model
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(article $model)
    {
        $model->delete();

        return redirect()->route('platform.article');
    }
}
