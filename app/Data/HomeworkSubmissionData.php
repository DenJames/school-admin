<?php

namespace App\Data;

use App\Models\HomeworkSubmission;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
class HomeworkSubmissionData extends Data
{
    public function __construct(
        public int                  $id,
        public Lazy|UserData        $userId,
        public Lazy|HomeworkData    $homeworkId,
        public string|null          $content,
        public string|null          $feedback,
    )
    {
    }

    public static function fromModel(HomeworkSubmission $homeworkSubmission): self
    {
        return new self(
            id: $homeworkSubmission->id,
            userId: Lazy::whenLoaded(
                'user',
                $homeworkSubmission,
                fn() => UserData::from($homeworkSubmission->user)
            ),
            homeworkId: Lazy::whenLoaded(
                'homework',
                $homeworkSubmission,
                fn() => HomeworkData::from($homeworkSubmission->homework)
            ),
            content: $homeworkSubmission->content,
            feedback: $homeworkSubmission->feedback,
        );
    }
}
