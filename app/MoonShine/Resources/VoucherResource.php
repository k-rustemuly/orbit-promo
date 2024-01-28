<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Voucher;
use App\MoonShine\Controllers\VoucherController;
use Illuminate\Contracts\Database\Query\Builder;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use Illuminate\Support\Facades\Route;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\QueryTags\QueryTag;

class VoucherResource extends ModelResource
{
    protected string $model = Voucher::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected array $with = [
        'user',
        'prize'
    ];

    public function title(): string
    {
        return __('ui.menu.vouchers');
    }

    public function getActiveActions(): array
    {
        return ['view'];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('ui.fields.user'), 'user', fn($item) => $item->phone_number, new UserResource()),

            BelongsTo::make(__('ui.fields.prize'), 'prize', fn($item) => "$item->name", new PrizeResource()),

            Preview::make(__('ui.fields.spent_balls'), 'spent_balls'),

            Switcher::make(__('ui.fields.is_approved'), 'is_approved'),

            Date::make(__('ui.fields.winning_date'), 'winned_date')
                    ->format('d.m.Y H:i')
                    ->withTime()
        ];
    }

    public function fields(): array
    {
        return [
            Block::make([

                Text::make(__('ui.fields.user'), 'user.name')
                    ->disabled(),

                Text::make(__('ui.fields.prize'), 'prize.name')
                    ->disabled(),

                Text::make(__('ui.fields.spent_balls'), 'spent_balls')
                    ->disabled(),

                Switcher::make(__('ui.fields.is_approved'), 'is_approved')
                    ->disabled(fn() => $this->getItem()?->winned_date == null),

                Date::make(__('ui.fields.winning_date'), 'winned_date')
                    ->format('d.m.Y H:i')
                    ->withTime()
                    ->disabled()

            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
        ];
    }

    public function search(): array
    {
        return [
            'user.name',
            'user.phone_number',
            'prize.name_ru',
            'prize.name_kk',
            'prize.name_uz',
        ];
    }
    /**
     * @return array<ActionButton>
     */
    public function detailButtons(): array
    {
        if($item = $this->getItem()) {
            if(! is_null($item->winned_date) && !$item->is_approved)
                return [
                    ActionButton::make(__('ui.buttons.approve'), fn($item) => route('moonshine.voucher.approve', ['resourceUri' => $this->uriKey(), 'voucher' => $item->id]))
                        ->withConfirm(
                            __('ui.buttons.approve'),
                            __('ui.messages.approve_body'),
                            __('ui.buttons.approve'),
                            method: 'GET'
                        )
                        ->success(),
                    ActionButton::make(__('ui.buttons.reject'), fn($item) => route('moonshine.voucher.reject', ['resourceUri' => $this->uriKey(), 'voucher' => $item->id]))
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

        Route::get('/voucher/{voucher}/approve', [VoucherController::class, 'approve'])->name('voucher.approve');
        Route::get('/voucher/{voucher}/reject', [VoucherController::class, 'reject'])->name('voucher.reject');

    }

    public function queryTags(): array
    {
        return [
            QueryTag::make(
                __('ui.buttons.to_approve'),
                fn(Builder $query) => $query->whereNotNull('winned_date')->where('is_approved', false)
            ),
        ];
    }
}
