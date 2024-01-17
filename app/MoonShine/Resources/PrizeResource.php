<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Prize;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Enums\PageType;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;

class PrizeResource extends ModelResource
{
    protected string $model = Prize::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    public function title(): string
    {
        return __('ui.menu.weekly_prizes');
    }

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
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
                    Column::make([
                        Number::make(__('ui.fields.bal'), 'bal')
                            ->hint(__('ui.hints.bal'))
                            ->required()
                            ->min(1)
                            ->buttons(),
                    ])->columnSpan(6),
                    Column::make([
                        Number::make(__('ui.fields.number'), 'number')
                            ->hint(__('ui.hints.number'))
                            ->required()
                            ->min(1)
                            ->buttons(),
                    ])->columnSpan(6),
                ]),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'name_ru' => 'nullable',
            'name_kk' => 'nullable',
            'name_uz' => 'nullable',
            'bal' => 'required|integer|min:0',
            'number' => 'required|integer|min:0',
        ];
    }
}
