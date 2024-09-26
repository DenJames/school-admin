<?php

namespace App\Data;

use App\Models\User;
use Illuminate\Support\Collection;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class UserData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int                  $id,
        public string               $name,
        public string               $email,
        public ?string              $emailVerifiedAt,
        public string               $isGroupAdmin,
        public ?string              $profilePhotoUrl,
        public Lazy|Collection|null $groups,
    )
    {
    }


    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at?->toDateTimeString(),
            isGroupAdmin: $user->isCurrentGroupAdmin(),
            profilePhotoUrl: $user->profile_photo_url,
            groups: $user->relationLoaded('groups')
                ? Lazy::whenLoaded(
                    'groups',
                    $user,
                    fn() => GroupData::collect($user->groups)
                )
                : null,
        );
    }
}
