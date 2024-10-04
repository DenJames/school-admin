<?php

namespace App\Data;

use App\Models\AssignedGrade;
use App\MomentumLock\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class GradeData extends DataResource
{
    protected string $modelClass = AssignedGrade::class;

    public function __construct(
        public int $id,
        public string $grade,
        public string $comment,
        public ?string $createdAt,
        public ?string $updatedAt,
        public Lazy|UserData $user,
        public Lazy|TeamData $team,
        public Lazy|TeacherData $teacher,
    )
    {
    }

    public static function fromModel(AssignedGrade $assignedGrade): self
    {
        return new self(
            id: $assignedGrade->id,
            grade: $assignedGrade->grade,
            comment: $assignedGrade->comment,
            createdAt: $assignedGrade->created_at?->format('Y-m-d'),
            updatedAt: $assignedGrade->updated_at?->format('Y-m-d'),
            user: Lazy::whenLoaded(
                'user',
                $assignedGrade,
                fn() => UserData::from($assignedGrade->user)
            ),
            team: Lazy::whenLoaded(
                'team',
                $assignedGrade,
                fn() => TeamData::from($assignedGrade->team)
            ),
            teacher: Lazy::whenLoaded(
                'teacher',
                $assignedGrade,
                fn() => TeacherData::from($assignedGrade->teacher)
            ),
        );
    }
}
