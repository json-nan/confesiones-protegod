<?php

namespace App\Enums;

enum QuestionStatusEnum: string
{
    use Arrayable;

    case OPEN = 'open';
    case ANSWERED = 'answered';
    case IGNORED = 'ignores';
}
