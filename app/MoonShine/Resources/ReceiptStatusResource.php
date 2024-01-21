<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReceiptStatus;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Color;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class ReceiptStatusResource extends ModelResource
{
    protected string $model = ReceiptStatus::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    public function title(): string
    {
        return __('ui.menu.receipt_statuses');
    }

    public function getActiveActions(): array
    {
        return ['view', 'update'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make(__('ui.fields.name_ru'), 'name_ru'),
                Text::make(__('ui.fields.name_kk'), 'name_kk'),
                Text::make(__('ui.fields.name_uz'), 'name_uz'),
                Color::make(__('ui.fields.color'), 'color')
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'name_ru' => 'nullable',
            'name_kk' => 'nullable',
            'name_uz' => 'nullable',
            'color' => 'required|hex_color'
        ];
    }
}
