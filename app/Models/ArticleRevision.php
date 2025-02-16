<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ArticleRevision extends Model
{
    use HasFactory;

    protected $fillable =  ['title', 'description', 'body', 'article_id', 'user_id'];
    
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function setTitleAttribute(string $title): void
    {
        $this->attributes['title'] = $title;

        $this->attributes['slug'] = Str::slug($title);
    }
}
