<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Settings\GeneralSettings;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\TableBuilder;
use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\LineBreak;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Pages\Page;

class SettingsPage extends Page
{
    public GeneralSettings $settings;

    public function __construct(?string $title = null, ?string $alias = null, ?ResourceContract $resource = null)
    {
        parent::__construct($title, $alias, $resource);
        $this->settings = app(GeneralSettings::class);
    }

    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return __('ui.menu.settings');
    }

    public function fields(): array
    {
        return [
            Number::make(__('ui.fields.game_max_coins'), 'game_max_coins')
                ->buttons()
                ->min(0)
                ->required(),
            Number::make(__('ui.fields.receipt_life'), 'receipt_life')
                ->buttons()
                ->min(0)
                ->required(),
            Number::make(__('ui.fields.referal_life'), 'referal_life')
                ->buttons()
                ->min(0)
                ->required(),
            DateRange::make(__('ui.fields.promotion_date'), 'promotion')
                ->fromTo('start_date', 'end_date')
                ->format('Y-m-d')
                ->required(),
        ];
    }

    public function values(): array
    {
        return [
            'game_max_coins' => $this->settings->game_max_coins,
            'receipt_life' => $this->settings->receipt_life,
            'referal_life' => $this->settings->referal_life,
            'start_date' => $this->settings->start_date,
            'end_date' => $this->settings->end_date,
        ];
    }

    public function components(): array
	{
        return [
            Block::make(
                [
                    TableBuilder::make($this->fields())
                        ->items([$this->values()])
                        ->vertical()
                        ->simple()
                        ->preview()
                        ->tdAttributes(fn (
                            $data,
                            int $row,
                            int $cell,
                            ComponentAttributeBag $attributes
                        ): ComponentAttributeBag => $attributes->when(
                            $cell === 0,
                            fn (ComponentAttributeBag $attr): ComponentAttributeBag => $attr->merge([
                                'class' => 'font-semibold',
                                'width' => '50%',
                            ])
                        )),

                        LineBreak::make(),

                        Flex::make([
                            ActionButton::make('')
                                ->inModal(
                                    fn () => __('moonshine::ui.edit'),
                                    fn () => FormBuilder::make(
                                        route('moonshine.settings.update', ['any']),
                                        fields: $this->fields(),
                                        values: $this->values()
                                    ),
                                )
                                ->primary()
                                ->icon('heroicons.outline.pencil')
                                ->customAttributes(['class' => 'edit-button'])
                                ->showInLine()
                        ])->justifyAlign('end'),
                ]
            )
        ];
	}
}
