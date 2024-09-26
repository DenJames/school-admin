<?php

namespace App\Data;

use App\Models\Message;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class MessageData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int           $id,
        public string        $subject,
        public string        $content,
        public ?string       $readAt,
        public ?string       $createdAt,
        public Lazy|UserData $sender,
        public Lazy|UserData $receiver,
        public Lazy|UserData $replies,
    )
    {
    }

    public static function fromModel(Message $message): self
    {
        return new self(
            id: $message->id,
            subject: $message->subject,
            content: $message->content,
            readAt: $message?->read_at,
            createdAt: $message?->created_at,
            sender: Lazy::whenLoaded(
                'sender',
                $message,
                fn() => UserData::from($message->sender)
            ),
            receiver: Lazy::whenLoaded(
                'receiver',
                $message,
                fn() => UserData::from($message->receiver)
            ),
            replies: Lazy::whenLoaded(
                'replies',
                $message,
                fn() => ReplyData::collect($message->replies)
            ),
        );
    }
}
