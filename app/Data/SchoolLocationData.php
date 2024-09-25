<?php

namespace App\Data;

use App\Models\SchoolLocation;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class SchoolLocationData extends Data
{
    public function __construct(
        public int    $id,
        public string $name,
        public Lazy|CityData $city,
        public Lazy|SchoolData $schools,
    )
    {
    }

    public static function fromModel(SchoolLocation $location): self
    {
        return new self(
            id: $location->id,
            name: $location->address,
            city: Lazy::whenLoaded(
                'city',
                $location,
                fn() => SchoolLocationData::fromModel($location->city)
            ),
            schools: Lazy::whenLoaded(
                'schools',
                $location,
                fn() => SchoolData::collect($location->schools)
            ),
        );
    }
}
