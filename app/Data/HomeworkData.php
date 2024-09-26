<?php

namespace App\Data;

use App\Models\Homework;
use App\MomentumLock\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class HomeworkData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int    $id,
        public string $name,
        public string $description,
        public string $due_date,
        public Lazy|LessonData $lesson,
    )
    {
    }

    public static function fromModel(Homework $homework): self
    {
        return new self(
            id: $homework->id,
            name: $homework->name,
            description: $homework->description,
            due_date: $homework->due_date,
            lesson: Lazy::whenLoaded(
                'lesson',
                $homework,
                fn() => LessonData::from($homework->lesson)
            ),
        );
    }
}
