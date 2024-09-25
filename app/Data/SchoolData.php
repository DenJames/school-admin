<?php

namespace App\Data;

use App\Models\School;
use App\Models\Team;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class SchoolData extends Data
{
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
                fn() => SchoolLocationData::fromModel($school->location)
            ),
        );
    }
}
