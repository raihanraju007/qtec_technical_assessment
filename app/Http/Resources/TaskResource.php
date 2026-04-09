<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->getLabel(),
                'labelBn' => $this->status->getLabelBn(),
            ],
            'priority' => [
                'value' => $this->priority->value,
                'label' => $this->priority->getLabel(),
                'labelBn' => $this->priority->getLabelBn(),
            ],
            'dueDate' => $this->due_date?->toDateString(),
            'isOverdue' => $this->due_date?->isPast() && $this->status->getCode() !== 'completed',
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
