<?php

namespace App\Data;

use App\Models\MessageReply;
use App\MomentumLock\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class ReplyData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int              $id,
        public int              $userId,
        public string           $content,
        public Lazy|MessageData $message,
        public Lazy|UserData    $user,
        public ?string          $createdAt = null,
        public ?string          $updatedAt = null,
    )
    {
    }

    public static function fromModel(MessageReply $reply): self
    {
        return new self(
            id: $reply->id,
            userId: $reply->user_id,
            content: $reply->content,
            message: Lazy::whenLoaded(
                'message',
                $reply,
                fn() => UserData::from($reply->message)
            ),
            user: Lazy::whenLoaded(
                'user',
                $reply,
                fn() => UserData::from($reply->user)
            ),
            createdAt: $reply->created_at,
            updatedAt: $reply->updated_at,
        );
    }
}
