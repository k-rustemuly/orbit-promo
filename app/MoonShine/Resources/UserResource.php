<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Models\MoonshineUserRole;

class UserResource extends ModelResource
{
    protected string $model = User::class;

    public function title(): string
    {
        return __('ui.menu.users');
    }

    public function getActiveActions(): array
    {
        if(auth('moonshine')->user()->moonshine_user_role_id === MoonshineUserRole::DEFAULT_ROLE_ID) {
            return ['view', 'delete'];
        }
        return ['view'];
    }


    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make(__('ui.fields.name'), 'name'),
                Text::make(__('ui.fields.phone_number'), 'phone_number'),
                Text::make(__('ui.fields.email'), 'email'),
                Text::make(__('ui.fields.life'), 'life')->sortable(),
                Text::make(__('ui.fields.level'), 'level')->sortable(),
                Text::make(__('ui.fields.coin'), 'coin')->sortable(),
                Date::make(__('ui.fields.created_at'), 'created_at')
                    ->withTime()
                    ->sortable(),
                HasMany::make(__('ui.fields.games'), 'games', resource: new GameResource())
                    ->fields([
                        Text::make(__('ui.fields.before_life'), 'before_life')->sortable(),
                        Text::make(__('ui.fields.after_life'), 'after_life')->sortable(),
                        Text::make(__('ui.fields.before_coins'), 'before_coins')->sortable(),
                        Text::make(__('ui.fields.coins'), 'coins')->sortable(),
                        Text::make(__('ui.fields.after_coins'), 'after_coins')->sortable(),
                        Text::make(__('ui.fields.before_level'), 'before_level')->sortable(),
                        Text::make(__('ui.fields.after_level'), 'after_level')->sortable(),
                        Text::make(__('ui.fields.score'), 'score')->sortable(),
                        Text::make(__('ui.fields.time'), 'time')->sortable(),
                        Switcher::make(__('ui.fields.is_finished'), 'is_finished'),
                        Date::make(__('ui.fields.created_at'), 'created_at')
                            ->withTime()
                            ->sortable(),
                    ])
                    ->hideOnIndex()
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
            'name',
            'phone_number',
            'email'
        ];
    }

}
