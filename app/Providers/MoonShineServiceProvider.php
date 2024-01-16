<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [
            new MoonShineUserRoleResource()
        ];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make(__('moonshine::ui.resource.admins_title'), new MoonShineUserResource())
                ->canSee(fn(Request $request) => $request->user('moonshine')?->moonshine_user_role_id == 1),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
