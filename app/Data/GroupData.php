<?php

namespace App\Data;

use App\Models\Group;
use Illuminate\Support\Collection;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class GroupData extends DataResource
{
    protected $permissions = ['update', 'delete', 'create'];

    public function __construct(
        public int                  $id,
        public int                  $userId,
        public int                  $teamId,
        public string               $name,
        public Lazy|Collection|null $users,
        public Lazy|UserData        $owner,
        public Lazy|Collection|null $invitations,
        public ?string              $createdAt = null,
        public ?string              $updatedAt = null,
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
            users: $group->relationLoaded('users')
                ? UserData::collect($group->users)
                : null,
            owner: Lazy::whenLoaded(
                'owner',
                $group,
                fn() => UserData::from($group->owner)),
            invitations: $group->relationLoaded('invitations')
                ? GroupInvitationData::collect($group->invitations)
                : null,
            createdAt: $group->created_at?->toDateTimeString(),
            updatedAt: $group->updated_at?->toDateTimeString(),
        );
    }
}
