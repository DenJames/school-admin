<?php

namespace App\Http\Controllers;

use App\Data\ArticleData;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        Gate::authorize('view', $article);

        return inertia('Articles/Show', [
            'article' => ArticleData::from($article->load('user', 'school', 'team')),
        ]);
    }
}
