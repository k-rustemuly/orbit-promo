<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\InstantPrize;
use App\MoonShine\Controllers\InstantPrizeController;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Json;
use MoonShine\Fields\Number;
use MoonShine\Fields\Position;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Models\MoonshineUserRole;
use MoonShine\QueryTags\QueryTag;

class InstantPrizeResource extends ModelResource
{
    protected string $model = InstantPrize::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected array $with = ['winner'];

    public function title(): string
    {
        return __('ui.menu.instant_prizes');
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
                Grid::make([
                    Column::make([
                        Text::make(__('ui.fields.name_ru'), 'name_ru')
                            ->showOnExport()
                            ->useOnImport(),
                    ])->columnSpan(4),
                    Column::make([
                        Text::make(__('ui.fields.name_kk'), 'name_kk')
                            ->showOnExport()
                            ->useOnImport(),
                    ])->columnSpan(4),
                    Column::make([
                        Text::make(__('ui.fields.name_uz'), 'name_uz')
                            ->showOnExport()
                            ->useOnImport(),
                    ])->columnSpan(4),
                ]),
                Text::make('code')
                    ->useOnImport()
                    ->showOnExport(auth('moonshine')->user()->moonshine_user_role_id === MoonshineUserRole::DEFAULT_ROLE_ID)
                    ->unless(
                        auth('moonshine')->user()->moonshine_user_role_id === MoonshineUserRole::DEFAULT_ROLE_ID,
                        fn(Field $field) => $field->hideOnForm()->hideOnIndex()->hideOnDetail(),
                    ),
                BelongsTo::make(__('ui.fields.winner'), 'winner', fn($item) => $item->phone_number, new UserResource())
                    ->showOnExport()
                    ->hideOnForm(),
                Date::make(__('ui.fields.winning_date'), 'winning_date')
                    ->format('d.m.Y H:i')
                    ->withTime()
                    ->showOnExport()
                    ->hideOnForm()
            ]),
        ];
    }

    /**
     * @return array<ActionButton>
     */
    public function actions(): array
    {
        return [
            ActionButton::make(__('ui.buttons.showbox_add'))
                ->inModal(
                    title: __('ui.buttons.showbox_add'),
                    content: fn() => form(route('moonshine.showbox.store', $this->uriKey()))
                        ->fields([
                            Grid::make([
                                Column::make([
                                    Text::make(__('ui.fields.name_ru'), 'name_ru'),
                                ])->columnSpan(3),
                                Column::make([
                                    Text::make(__('ui.fields.name_kk'), 'name_kk'),
                                ])->columnSpan(3),
                                Column::make([
                                    Text::make(__('ui.fields.name_uz'), 'name_uz'),
                                ])->columnSpan(3),
                                Column::make([
                                    Number::make(__('ui.fields.count'), 'count')
                                        ->buttons()
                                        ->min(1)
                                        ->required(),
                                ])->columnSpan(3),
                            ]),
                        ]),
                    wide: true
                )
                ->primary()
                ->icon('heroicons.plus-small'),

            ActionButton::make(__('ui.buttons.balance_add'))
                ->inModal(
                    title: __('ui.buttons.balance_add'),
                    content: fn() => form(route('moonshine.balance.store', $this->uriKey()))
                        ->fields([
                            Grid::make([
                                Column::make([
                                    Text::make(__('ui.fields.name_ru'), 'name_ru'),
                                ])->columnSpan(4),
                                Column::make([
                                    Text::make(__('ui.fields.name_kk'), 'name_kk'),
                                ])->columnSpan(4),
                                Column::make([
                                    Text::make(__('ui.fields.name_uz'), 'name_uz'),
                                ])->columnSpan(4),
                            ]),
                            Json::make(__('ui.fields.codes'), 'codes')
                            ->fields([
                                Position::make(),
                                Text::make(__('ui.fields.code'), 'code')
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


    public function rules(Model $item): array
    {
        return [
            'name_ru' => 'nullable',
            'name_kk' => 'nullable',
            'name_uz' => 'nullable',
            'code' => 'nullable',
        ];
    }

    public function search(): array
    {
        return [
            'name_ru',
            'name_kk',
            'name_uz',
            'winner.phone_number',
            'winner.name'
        ];
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('/showbox/store', [InstantPrizeController::class, 'showboxStore'])->name('showbox.store');
        Route::post('/balance/store', [InstantPrizeController::class, 'balanceStore'])->name('balance.store');

    }

    public function queryTags(): array
    {
        return [
            QueryTag::make(
                __('ui.buttons.winned'),
                fn(Builder $query) => $query->whereNotNull('winning_date')
            ),
            QueryTag::make(
                __('ui.buttons.free'),
                fn(Builder $query) => $query->whereNull('winning_date')
            )
        ];
    }

    public function filters(): array
    {
        return [
            Text::make(__('ui.fields.name_ru'), 'name_ru'),
            Text::make(__('ui.fields.name_kk'), 'name_kk'),
            Text::make(__('ui.fields.name_uz'), 'name_uz'),
            DateRange::make(__('ui.fields.winning_date'), 'winning_date')->withTime()
        ];
    }

    public function import(): ?ImportHandler
    {
        return ImportHandler::make(__('ui.buttons.import'))
            ->disk('public')
            ->dir('/imports')
            ->deleteAfter();
    }

    public function export(): ?ExportHandler
    {
        return ExportHandler::make(__('ui.buttons.export'));
    }
}
