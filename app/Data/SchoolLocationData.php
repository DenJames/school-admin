<?php

namespace App\Data;

use App\Models\SchoolLocation;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class SchoolLocationData extends DataResource
{
    protected $permissions = ['update', 'delete'];

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
                fn() => SchoolLocationData::from($location->city)
            ),
            schools: Lazy::whenLoaded(
                'schools',
                $location,
                fn() => SchoolData::collect($location->schools)
            ),
        );
    }
}
