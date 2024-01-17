<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Voucher;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;

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
        return ['view', 'update'];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make(__('ui.fields.user'), 'user', fn($item) => $item->name, new UserResource()),

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
            'is_approved' => 'bool'
        ];
    }

    public function search(): array
    {
        return [
            'user.name',
            'prize.name_ru',
            'prize.name_kk',
            'prize.name_uz',
        ];
    }
}
