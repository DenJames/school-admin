<?php

namespace App\Data;

use App\Models\Classroom;
use App\MomentumLock\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class ClassroomData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int    $id,
        public string $name,
        public Lazy|SchoolData $school,
    )
    {
    }

    public static function fromModel(Classroom $classroom): self
    {
        return new self(
            id: $classroom->id,
            name: $classroom->name,
            school: Lazy::whenLoaded(
                'school',
                $classroom,
                fn() => SchoolData::from($classroom->school)
            ),
        );
    }
}
