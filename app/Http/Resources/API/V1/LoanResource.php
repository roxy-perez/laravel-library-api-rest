<?php

namespace App\Http\Resources\API\V1;

use App\Http\Resources\API\V1\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @mixin \App\Models\Loan
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book' => new BookResource($this->whenLoaded('book')),
            'loaned_at' => $this->loaned_at,
            'returned_at' => $this->returned_at,
            'due_date' => $this->due_date,
            'returned' => $this->returned,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
