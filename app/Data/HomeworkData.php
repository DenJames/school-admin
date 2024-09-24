<?php

namespace App\Data;

use App\Models\Homework;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class HomeworkData extends Data
{
    public function __construct(
        public int              $id,
        public Lazy|LessonData  $lesson,
        public string           $name,
        public string           $description,
        public string           $dueDate,
        public string           $route,
        public string           $dueDateForHumans,
    )
    {
    }

    public static function fromModel(Homework $homework): self
    {
        return new self(
            id: $homework->id,
            lesson: Lazy::whenLoaded(
                'lesson',
                $homework,
                fn() => LessonData::from($homework->lesson)
            ),
            name: $homework->name,
            description: $homework->description,
            dueDate: $homework->due_date,
            route: route('homework.show', $homework),
            dueDateForHumans: Carbon::parse($homework->due_date)->diffForHumans()
        );
    }
}
