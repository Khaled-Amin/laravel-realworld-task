<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\ArticleRevision;

class ArticleRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $article;
    protected $revision;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->article = Article::factory()->create(['user_id' => $this->user->id]);
        $this->revision = ArticleRevision::factory()->create(['article_id' => $this->article->id, 'user_id' => $this->user->id]);
    }

    public function testGetAllRevisionsForArticle()
    {
        $this->actingAs($this->user);


        ArticleRevision::factory()->count(3)->create(['article_id' => $this->article->id]);

        $response = $this->getJson("/api/articles/{$this->article->id}/revisions");

        $response->assertJson([
            'articlesRevisionsCount' => 4,
        ]);
        foreach ($response->json('revisions') as $revision) {
            $this->assertArrayHasKey('article_id', $revision);
            $this->assertArrayHasKey('user_id', $revision);
            $this->assertArrayHasKey('title', $revision);
            $this->assertArrayHasKey('description', $revision);
            $this->assertArrayHasKey('body', $revision);
            $this->assertArrayHasKey('created_at', $revision);
        }
    }

    public function testGetSpecificRevision()
    {
        $this->actingAs($this->user);

        $response = $this->getJson("/api/articles/{$this->article->id}/revisions/{$this->revision->id}");

        $response->assertExactJson([
            'revision' => [
                'article_id'  => $this->revision->article_id,
                'user_id'     => $this->revision->user_id,
                'title'       => $this->revision->title,
                'description' => $this->revision->description,
                'body'        => $this->revision->body,
                'created_at'  => $this->revision->created_at,
            ]
        ]);
    }

    public function testCanRevertArticleToSpecificRevision()
    {
        $this->actingAs($this->user);

        $response = $this->putJson("/api/articles/{$this->article->id}/revisions/{$this->revision->id}/revert");

        $response->assertExactJson([
            'revision' => [
                'article_id'  => $this->revision->article_id,
                'user_id'     => $this->revision->user_id,
                'title'       => $this->revision->title,
                'description' => $this->revision->description,
                'body'        => $this->revision->body,
                'created_at'  => $this->revision->created_at,
            ]
        ]);

        $this->article->refresh();

        //=== check if article has been reverted 
        $this->assertEquals($this->article->title, $this->revision->title);
        $this->assertEquals($this->article->description, $this->revision->description);
        $this->assertEquals($this->article->body, $this->revision->body);
    }
}
