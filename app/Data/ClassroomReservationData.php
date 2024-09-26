<?php

namespace App\Data;

use App\Models\ClassroomReservation;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class ClassroomReservationData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int $id,
        public string $booked_from,
        public string $booked_to,
        public Lazy|ClassroomData $classroom,
    )
    {
    }

    public static function fromModel(ClassroomReservation $classroomReservation): self
    {
        return new self(
            id: $classroomReservation->id,
            booked_from: $classroomReservation->booked_from,
            booked_to: $classroomReservation->booked_to,
            classroom: Lazy::whenLoaded(
                'classroom',
                $classroomReservation,
                fn() => ClassroomData::from($classroomReservation->classroom)
            ),
        );
    }
}
