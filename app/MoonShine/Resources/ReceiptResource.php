<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Receipt;
use App\Models\ReceiptStatus;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use VI\MoonShineSpatieMediaLibrary\Fields\MediaLibrary;

class ReceiptResource extends ModelResource
{
    protected string $model = Receipt::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    public function title(): string
    {
        return __('ui.menu.receipts');
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
                MediaLibrary::make(__('ui.fields.image'), 'images')->disabled(),
                BelongsTo::make(__('ui.fields.status'), 'status', fn($item) => $item->name, new ReceiptStatusResource())
                    ->when(fn($field) => $field->getData()?->id != ReceiptStatus::CHECKING, fn(Field $field) => $field->disabled())
                    ->changePreview(fn($status, Field $field) => view('badge', [
                        'color' => $status->color,
                        'value' => $status->name
                    ])),
                Date::make(__('ui.fields.created_at'), 'created_at')
                    ->withTime()
                    ->format('Y-m-d H:i')
                    ->disabled(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
