<?php

namespace App\Data;

use App\Models\Group;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class GroupData extends Data
{
    public function __construct(
        public int           $id,
        public int           $userId,
        public int           $teamId,
        public string        $name,
        public Lazy|UserData $users,
        public ?string       $createdAt = null,
        public ?string       $updatedAt = null,
    )
    {
    }

    public static function fromModel(Group $group): self
    {
        return new self(
            id: $group->id,
            userId: $group->user_id,
            teamId: $group->team_id,
            name: $group->name,
            users: Lazy::whenLoaded(
                'users',
                $group,
                fn() => UserData::collect($group->users->pivot->map(fn($pivot) => $pivot->user))
            ),
            createdAt: $group->created_at,
            updatedAt: $group->updated_at,
        );
    }
}
