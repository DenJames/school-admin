<?php

namespace App\Data;

use App\Models\MessageReply;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class ReplyData extends Data
{
    public function __construct(
        public int              $id,
        public string           $content,
        public Lazy|MessageData $message,
        public Lazy|UserData    $user,
    )
    {
    }

    public static function fromModel(MessageReply $reply): self
    {
        return new self(
            id: $reply->id,
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
        );
    }
}
