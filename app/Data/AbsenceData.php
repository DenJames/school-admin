<?php

namespace App\Data;

use App\Models\Absence;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class AbsenceData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

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
                fn() => UserData::from($absence->user)
            ),
            lesson: Lazy::whenLoaded(
                'lesson',
                $absence,
                fn() => LessonData::from($absence->lesson)
            ),
            teacher: Lazy::whenLoaded(
                'teacher',
                $absence,
                fn() => TeacherData::from($absence->teacher)
            ),
        );
    }
}
