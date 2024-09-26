<?php

namespace App\Data;

use App\Models\School;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class SchoolData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int    $id,
        public string $name,
        public Lazy|SchoolLocationData $location,
    )
    {
    }

    public static function fromModel(School $school): self
    {
        return new self(
            id: $school->id,
            name: $school->name,
            location: Lazy::whenLoaded(
                'location',
                $school,
                fn() => SchoolLocationData::from($school->location)
            ),
        );
    }
}
