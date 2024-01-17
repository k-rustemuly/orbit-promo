<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Prize;
use Illuminate\Database\Eloquent\Model;
use App\Models\PrizeDrawingCalendar;
use App\MoonShine\Controllers\PrizeDrawingCalenderController;
use DateTime;
use Illuminate\Support\Facades\Route;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Json;
use MoonShine\Fields\Number;
use MoonShine\Fields\Position;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;
use MoonShine\Fields\Switcher;

class PrizeDrawingCalendarResource extends ModelResource
{
    protected string $model = PrizeDrawingCalendar::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected array $with = ['prize'];

    public function title(): string
    {
        return __('ui.menu.prize_drawing_calendars');
    }

    public function getActiveActions(): array
    {
        return ['view', 'update', 'delete'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),

                BelongsTo::make(__('ui.fields.prize'), 'prize', fn($item) => $item->name, new PrizeResource())
                    ->required(),

                Number::make(__('ui.fields.count'), 'number')
                    ->required()
                    ->min(1),

                Date::make(__('ui.fields.drawing_at'), 'drawing_at')
                    ->withTime()
                    ->format('d.m.Y H:i')
                    ->required(),

                Date::make(__('ui.fields.started_at'), 'started_at')
                    ->withTime()
                    ->format('d.m.Y H:i')
                    ->hideOnForm(),

                Switcher::make(__('ui.fields.is_finished'), 'is_finished')
                    ->hideOnForm()
            ]),
        ];
    }

    public function search(): array
    {
        return [
            'prize.name_ru',
            'prize.name_kk',
            'prize.name_uz',
            'number'
        ];
    }


    public function rules(Model $item): array
    {
        return [];
    }



    /**
     * @return array<ActionButton>
     */
    public function actions(): array
    {
        return [
            ActionButton::make(__('ui.buttons.add'))
                ->inModal(
                    title: __('ui.buttons.add'),
                    content: fn() => form(route('moonshine.mass.store', $this->uriKey()))
                        ->fields([
                            Grid::make([
                                Column::make([
                                    Date::make(__('ui.fields.start_date'), 'started_at')
                                        ->withTime()
                                        ->required(),
                                ])->columnSpan(6),

                                Column::make([
                                    Number::make(__('ui.fields.repeat_weeks'), 'repeat_weeks')
                                        ->buttons()
                                        ->min(1)
                                        ->required(),
                                    ])->columnSpan(6)
                            ]),

                            Json::make(__('ui.fields.prizes'), 'prizes')
                                ->fields([
                                    Position::make(),

                                    Select::make(__('ui.fields.prize'), 'prize')
                                        ->options(Prize::all()->pluck('name', 'id')->toArray()),

                                    Number::make(__('ui.fields.count'), 'number')
                                        ->buttons()
                                        ->min(1)
                                        ->default(1)
                                        ->required()
                                ])
                                ->sortable()
                                ->creatable()
                                ->removable(),
                        ]),
                    wide: true
                )
                ->primary()
                ->icon('heroicons.plus-small'),
        ];
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('/mass/store', [PrizeDrawingCalenderController::class, 'massStore'])->name('mass.store');

    }
}
