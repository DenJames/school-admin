<?php

namespace App\Data;

use App\Models\GroupInvitation;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class GroupInvitationData extends Data
{
    public function __construct(
        public int            $id,
        public int            $groupId,
        public int            $userId,
        public string         $email,
        public Lazy|GroupData $group,
        public Lazy|UserData  $user,
        public ?string        $createdAt = null,
        public ?string        $updatedAt = null,
    )
    {
    }

    public static function fromModel(GroupInvitation $groupInvitation): self
    {
        return new self(
            id: $groupInvitation->id,
            groupId: $groupInvitation->group_id,
            userId: $groupInvitation->user_id,
            email: $groupInvitation->email,
            group: Lazy::whenLoaded(
                'group',
                $groupInvitation,
                fn() => GroupData::fromModel($groupInvitation->group)),
            user: Lazy::whenLoaded(
                'user',
                $groupInvitation,
                fn() => UserData::fromModel($groupInvitation->user)),
            createdAt: $groupInvitation->created_at?->toDateTimeString(),
            updatedAt: $groupInvitation->updated_at?->toDateTimeString(),
        );
    }
}
