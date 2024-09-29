<?php

namespace App\Data;

use App\Models\Article;
use App\MomentumLock\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class ArticleData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int $id,
        public bool $isGlobal,
        public string $title,
        public string $content,
        public string|null $publishedAt,
        public Lazy|UserData $user,
        public Lazy|SchoolData $school,
        public Lazy|TeamData $team,
    )
    {
    }


    public static function fromModel(Article $article): self
    {
        return new self(
            id: $article->id,
            isGlobal: $article->is_global,
            title: $article->title,
            content: $article->content,
            publishedAt: $article->published_at?->toDateTimeString(),
            user: Lazy::whenLoaded('user', $article, fn() => UserData::fromModel($article->user)),
            school: Lazy::whenLoaded('school', $article, fn() => SchoolData::fromModel($article->school)),
            team: Lazy::whenLoaded('team', $article, fn() => TeamData::fromModel($article->team)),
        );
    }
}
