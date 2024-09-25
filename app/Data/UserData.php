<?php

namespace App\Data;

use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class UserData extends Data
{
    public function __construct(
        public int                  $id,
        public string               $name,
        public string               $email,
        public ?string              $emailVerifiedAt,
        public string               $isGroupAdmin,
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
