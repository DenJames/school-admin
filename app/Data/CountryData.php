<?php

namespace App\Data;

use App\Models\Country;
use Momentum\Lock\Data\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class CountryData extends DataResource
{
    protected $permissions = ['update', 'delete'];

    public function __construct(
        public int    $id,
        public string $name,
        public string $iso,
        public Lazy|CityData $cities,
    )
    {
    }

    public static function fromModel(Country $country): self
    {
        return new self(
            id: $country->id,
            name: $country->name,
            iso: $country->iso,
            cities: Lazy::whenLoaded(
                'cities',
                $country,
                fn() => CityData::collect($country->cities)
            ),
        );
    }
}
