<?php

namespace App\Data;

use App\Models\Absence;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class AbsenceData extends Data
{
    public function __construct(
        public int $id,
        public string $reason,
        public bool $excused,
        public string $approvedAt,
        public Lazy|UserData $user,
        public Lazy|LessonData $lesson,
        public Lazy|TeacherData $teacher,
    )
    {
    }

    public static function fromModel(Absence $absence): self
    {
        return new self(
            id: $absence->id,
            reason: $absence->reason,
            excused: $absence->excused,
            approvedAt: $absence->approved_at,
            user: Lazy::whenLoaded(
                'user',
                $absence,
                fn() => UserData::fromModel($absence->user)
            ),
            lesson: Lazy::whenLoaded(
                'lesson',
                $absence,
                fn() => LessonData::fromModel($absence->lesson)
            ),
            teacher: Lazy::whenLoaded(
                'teacher',
                $absence,
                fn() => TeacherData::fromModel($absence->teacher)
            ),
        );
    }
}
