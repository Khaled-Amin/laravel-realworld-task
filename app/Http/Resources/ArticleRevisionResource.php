<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleRevisionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = 'revision';

    public function toArray($request): array
    {
        return [
            'article_id'    => $this->article_id,
            'user_id'       => $this->user_id,
            'title'         => $this->title,
            'description'   => $this->description,
            'body'          => $this->body,
            'created_at'    => $this->created_at
        ];
    }
}
