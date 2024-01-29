<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invitation;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class InvitationResource extends ModelResource
{
    protected string $model = Invitation::class;

    public function title(): string
    {
        return __('ui.menu.invitations');
    }

    public function getActiveActions(): array
    {
        return [];
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
