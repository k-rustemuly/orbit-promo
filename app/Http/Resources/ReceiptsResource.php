<?php

namespace App\Http\Resources;

use App\Models\ReceiptStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->created_at->format('Y-m-d'),
            'number' => $this->receipt_status_id == ReceiptStatus::CHECKING ? $this->status->name : $this->id
        ];
    }
}
