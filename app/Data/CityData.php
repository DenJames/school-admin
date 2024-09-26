<?php

namespace App\Data;

use App\Models\City;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class CityData extends DataResource
{
    protected $permissions = ['update', 'delete'];

    public function __construct(
        public int    $id,
        public string $name,
        public string $zipCode,
        public Lazy|CountryData $country,
    )
    {
    }

    public static function fromModel(City $city): self
    {
        return new self(
            id: $city->id,
            name: $city->name,
            zipCode: $city->zip_code,
            country: Lazy::whenLoaded(
                'country',
                $city,
                fn() => CountryData::from($city->country)
            ),
        );
    }
}
