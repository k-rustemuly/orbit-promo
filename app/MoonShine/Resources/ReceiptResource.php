<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Receipt;
use App\Models\ReceiptStatus;
use App\MoonShine\Controllers\ReceiptController;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Resources\ModelResource;
use Illuminate\Support\Facades\Route;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use Illuminate\Contracts\Database\Query\Builder;
use MoonShine\QueryTags\QueryTag;
use VI\MoonShineSpatieMediaLibrary\Fields\MediaLibrary;

class ReceiptResource extends ModelResource
{
    protected string $model = Receipt::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected array $with = ['user', 'media', 'status'];

    public function title(): string
    {
        return __('ui.menu.receipts');
    }

    public function getActiveActions(): array
    {
        return ['view'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Preview::make(__('ui.fields.receipt_id'), 'url')
                    ->link(fn($item) => $item, blank: true),
                BelongsTo::make(__('ui.fields.user'), 'user', fn($item) => $item->phone_number, new UserResource())->disabled(),
                MediaLibrary::make(__('ui.fields.image'), 'images')->disabled(),
                BelongsTo::make(__('ui.fields.status'), 'status', fn($item) => $item->name, new ReceiptStatusResource())
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

    public function search(): array
    {
        return [
            'url',
            'user.name',
            'user.phone_number',
        ];
    }

    /**
     * @return array<ActionButton>
     */
    public function detailButtons(): array
    {
        if($this->getItem()?->receipt_status_id == ReceiptStatus::CHECKING) {
            return [
                ActionButton::make(__('ui.buttons.approve'), fn($item) => route('moonshine.receipt.approve', ['resourceUri' => $this->uriKey(), 'receipt' => $item->id]))
                    ->withConfirm(
                        __('ui.buttons.approve'),
                        __('ui.messages.approve_body'),
                        __('ui.buttons.approve'),
                        [
                            Text::make(__('ui.fields.receipt_id'), 'url')->required()
                        ],
                    )
                    ->success(),
                ActionButton::make(__('ui.buttons.reject'), fn($item) => route('moonshine.receipt.reject', ['resourceUri' => $this->uriKey(), 'receipt' => $item->id]))
                    ->withConfirm(
                        __('ui.buttons.reject'),
                        __('ui.messages.reject_body'),
                        __('ui.buttons.reject'),
                        method: 'GET'
                    )
                    ->error(),
            ];
        }
        return [];
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('/{receipt}/approve', [ReceiptController::class, 'approve'])->name('receipt.approve');
        Route::get('/{receipt}/reject', [ReceiptController::class, 'reject'])->name('receipt.reject');

    }

    public function queryTags(): array
    {
        return [
            QueryTag::make(
                __('ui.buttons.to_approve'),
                fn(Builder $query) => $query->where('receipt_status_id', ReceiptStatus::CHECKING)
            ),
        ];
    }
}
