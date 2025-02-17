<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ArticleRevision;
use App\Models\Article;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleRevision>
 */
class ArticleRevisionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'article_id'  => Article::factory(),
            'user_id'     => fn(array $attributes) => Article::find($attributes['article_id'])->user_id,
            'title'       => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'body'        => $this->faker->text(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
