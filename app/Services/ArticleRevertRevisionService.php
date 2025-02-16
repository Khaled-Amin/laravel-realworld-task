<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleRevision;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ArticleRevertRevisionService
{

    public function storeRevision(Article $article)
    {
        ArticleRevision::create([
            'article_id'    => $article->id,
            'user_id'       => Auth::user()->id,
            'title'         => $article->getOriginal('title'),
            'description'   => $article->getOriginal('description'),
            'body'          => $article->getOriginal('body'),
        ]);
    }

    public function getRevisions(int $articleId): Collection
    {
        return Article::findOrFail($articleId)->revisions()->latest()->get();
    }

    public function getRevision(int $articleId, int $revisionId): ArticleRevision
    {
        $article = Article::findOrFail($articleId);

        if(!$article->revisions()->find($revisionId)) {
            throw new ModelNotFoundException('Revision not found');
        }

        return $article->revisions()->findOrFail($revisionId);
    }

    
    public function revertRevision(int $articleId, int $revisionId): ArticleRevision
    {
        return DB::transaction(function () use ($articleId, $revisionId) {
            $article = $this->getArticleById($articleId);
            $revision = $this->getRevisionForArticle($revisionId, $articleId);

            $this->updateArticleFromRevision($article, $revision);

            return $revision;
        });
    }

    /**
     * Get the revision for the article
     * @param int $revisionId
     * @param int $articleId
     * @return ArticleRevision
     */
    protected function getRevisionForArticle(int $revisionId, int $articleId): ArticleRevision
    {
        return ArticleRevision::where('id', $revisionId)->where('article_id', $articleId)->firstOrFail();
    }


    /**
     * Get the article by id
     * @param int $articleId
     * @return Article
     */
    protected function getArticleById(int $articleId): Article
    {
        return Article::findOrFail($articleId);
    }


    /*
    * Update the article from the revision
    *
     * @param Article $article
     * @param ArticleRevision $revision
     * @return void
     */
    protected function updateArticleFromRevision(Article $article, ArticleRevision $revision): void
    {
        $article->update([
            'title' => $revision->title,
            'description' => $revision->description,
            'body' => $revision->body,
        ]);
    }
}
