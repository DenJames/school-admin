<?php

namespace App\Data;

use App\Models\Teacher;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class TeacherData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

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
                fn() => SchoolData::from($teacher->school)
            ),
            user: Lazy::whenLoaded(
                'user',
                $teacher,
                fn() => UserData::from($teacher->user)
            ),
        );
    }
}
