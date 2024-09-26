<?php

namespace App\Data;

use App\Actions\Lesson\FetchAbsence;
use App\Models\Lesson;
use Illuminate\Support\Collection;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class LessonData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int    $id,
        public string $name,
        public ?string $description,
        public int   $duration,
        public string $start,
        public string $end,
        public Lazy|TeamData $team,
        public Lazy|TeacherData $teacher,
        public Lazy|ClassCategoryData $classCategory,
        public Lazy|ClassroomReservationData $classroomReservation,
        public Lazy|HomeworkData $homeworks,
        public Collection $absences,
    )
    {
    }

    public static function fromModel(Lesson $lesson): self
    {
        $absence = new FetchAbsence($lesson);

        return new self(
            id: $lesson->id,
            name: $lesson->name,
            description: $lesson->description,
            duration: $lesson->duration,
            start: $lesson->starts_at->format('c'),
            end: $lesson->ends_at->format('c'),
            team: Lazy::whenLoaded('team', $lesson, fn() => TeamData::from($lesson->team)),
            teacher: Lazy::whenLoaded('teacher', $lesson, fn() => TeacherData::from($lesson->teacher)),
            classCategory: Lazy::whenLoaded('classCategory', $lesson, fn() => ClassCategoryData::from($lesson->classCategory)),
            classroomReservation: Lazy::whenLoaded('classroomReservation', $lesson, fn() => ClassroomReservationData::from($lesson->classroomReservation)),
            homeworks: Lazy::whenLoaded('homeworks', $lesson, fn() => HomeworkData::collect($lesson->homeworks)),
            absences: $absence->all() ? AbsenceData::collect($absence->all()) : [],
        );
    }
}
