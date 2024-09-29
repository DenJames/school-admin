<?php

namespace App\Data;

use App\Models\Team;
use App\MomentumLock\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class TeamData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int    $id,
        public string $name,
        public Lazy|UserData $owner,
        public Lazy|SchoolData $school,
        public Lazy|ArticleData $articles,
    )
    {
    }

    public static function fromModel(Team $team): self
    {
        return new self(
            id: $team->id,
            name: $team->name,
            owner: Lazy::whenLoaded(
                'owner',
                $team,
                fn() => UserData::from($team->owner)
            ),
            school: Lazy::whenLoaded(
                'school',
                $team,
                fn() => SchoolData::from($team->school)
            ),
            articles: Lazy::whenLoaded(
                'articles',
                $team,
                fn() => ArticleData::collect($team->articles)
            ),
        );
    }
}
