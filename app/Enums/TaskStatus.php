<?php

namespace App\Enums;

enum TaskStatus: int
{
    case PENDING = 1;
    case IN_PROGRESS = 2;
    case COMPLETED = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
        };
    }

    public function getLabelBn(): string
    {
        return match ($this) {
            self::PENDING => 'মুলতুবি',
            self::IN_PROGRESS => 'চলমান',
            self::COMPLETED => 'সম্পন্ন',
        };
    }
}
