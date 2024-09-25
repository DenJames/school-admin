<?php

namespace App\Data;

use App\Models\Teacher;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class TeacherData extends Data
{
    public function __construct(
        public int $id,
        public Lazy|SchoolData $school,
        public Lazy|UserData $user,
    )
    {
    }

    public static function fromModel(Teacher $teacher): self
    {
        return new self(
            id: $teacher->id,
            school: Lazy::whenLoaded(
                'school',
                $teacher,
                fn() => SchoolData::fromModel($teacher->school)
            ),
            user: Lazy::whenLoaded(
                'user',
                $teacher,
                fn() => UserData::fromModel($teacher->user)
            ),
        );
    }
}
