<?php

namespace App\Data;

use App\Models\ClassCategory;
use App\MomentumLock\DataResource;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class ClassCategoryData extends DataResource
{
    protected $permissions = ['viewAny', 'view', 'create', 'update', 'delete'];

    public function __construct(
        public int $id,
        public string $name,
    )
    {
    }

    public static function fromModel(ClassCategory $classCategory): self
    {
        return new self(
            id: $classCategory->id,
            name: $classCategory->name,
        );
    }
}
