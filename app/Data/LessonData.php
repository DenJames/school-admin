<?php

namespace App\Data;

use App\Models\Lesson;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class LessonData extends Data
{
    public function __construct(
        public int    $id,
        public string $name,
        public ?string $description,
        public int   $duration,
        public string $startsAt,
        public Lazy|TeamData $team,
        public Lazy|TeacherData $teacher,
        public Lazy|ClassCategoryData $classCategory,
        public Lazy|ClassroomReservationData $classroomReservation,
        public Lazy|HomeworkData $homeworks,
        public Lazy|AbsenceData $absences,
    )
    {
    }

    public static function fromModel(Lesson $lesson): self
    {
        return new self(
            id: $lesson->id,
            name: $lesson->name,
            description: $lesson->description,
            duration: $lesson->duration,
            startsAt: $lesson->starts_at->format('c'),
            team: Lazy::whenLoaded('team', $lesson, fn() => TeamData::fromModel($lesson->team)),
            teacher: Lazy::whenLoaded('teacher', $lesson, fn() => TeacherData::fromModel($lesson->teacher)),
            classCategory: Lazy::whenLoaded('classCategory', $lesson, fn() => ClassCategoryData::fromModel($lesson->classCategory)),
            classroomReservation: Lazy::whenLoaded('classroomReservation', $lesson, fn() => ClassroomReservationData::fromModel($lesson->classroomReservation)),
            homeworks: Lazy::whenLoaded('homeworks', $lesson, fn() => HomeworkData::collect($lesson->homeworks)),
            absences: Lazy::whenLoaded('absences', $lesson, fn() => AbsenceData::collect($lesson->absences)),
        );
    }
}
