<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class GameResource extends ModelResource
{
    protected string $model = Game::class;

    public function title(): string
    {
        return __('ui.menu.games');
    }

    public function getActiveActions(): array
    {
        return [];
        // return ['view'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
