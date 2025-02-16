<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\RevertRevisionRequest;
use App\Http\Resources\ArticleRevisionCollection;
use App\Http\Resources\ArticleRevisionResource;
use App\Models\Article;
use App\Models\ArticleRevision;
use App\Services\ArticleRevertRevisionService;
use Illuminate\Http\Request;

class ArticleRevertRevisionController extends Controller
{
    protected ArticleRevertRevisionService $articleRevisionService;

    public function __construct(ArticleRevertRevisionService $articleRevisionService)
    {
        $this->articleRevisionService = $articleRevisionService;
    }

    public function index(int $articleId, RevertRevisionRequest $request): ArticleRevisionCollection
    {
        return new ArticleRevisionCollection($this->articleRevisionService->getRevisions($articleId));
    }

    public function show(int $articleId, int $revisionId, RevertRevisionRequest $request): ArticleRevisionResource
    {
        return $this->articleRevisionResponse($this->articleRevisionService->getRevision($articleId, $revisionId));
    }

    public function revert(int $articleId, int $revisionId, RevertRevisionRequest $request): ArticleRevisionResource
    {
        try{
            return $this->articleRevisionResponse($this->articleRevisionService->revertRevision($articleId, $revisionId));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Revision not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
        
    }

    protected function articleRevisionResponse(ArticleRevision $revision): ArticleRevisionResource
    {
        return new ArticleRevisionResource($revision);
    }
}
