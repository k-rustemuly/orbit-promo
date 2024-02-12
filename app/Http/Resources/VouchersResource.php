<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VouchersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->is_approved && ! is_null($this->winned_date) ? $this->winned_date->format('d.m.y') : $this->created_at->format('d.m.Y'),
            'phone_number' => $this->user->hidden_phone_number,
            'spent_bal' =>  $this->spent_balls,
            'prize' => $this->prize->name,
            'is_winned' => $this->is_approved && ! is_null($this->winned_date),
        ];
    }
}
