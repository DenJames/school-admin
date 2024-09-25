<?php

namespace App\Data;

use App\Models\Lesson;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class LessonData extends Data
{
    public function __construct(
        public int     $id,
        public int     $teamId,
        public int     $classCategoryId,
        public int     $teacherId,
        public string  $name,
        public int     $duration,
        public string  $startsAt
    )
    {
    }

    public static function fromModel(Lesson $lesson): self
    {
        return new self(
            id: $lesson->id,
            teamId: $lesson->team_id,
            classCategoryId: $lesson->class_category_id,
            teacherId: $lesson->teacher_id,
            name: $lesson->name,
            duration: $lesson->duration,
            startsAt: $lesson->starts_at
        );
    }
}
