<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassroomCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function($model){
                return [
                    'name' => $model->name,
                    'code' => $model->code,
                    'meta' => [
                        'section' => $model->section,
                        'room' => $model->room,
                        'subject' => $model->subject,
                        'students_count' => $model->students_count ?? 0,
                    ],
                    'user' => [
                        'name' => $model->user->name
                    ],
                    'topics' => $model->topics
                ];
            }),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
