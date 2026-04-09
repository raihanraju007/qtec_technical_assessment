<?php

namespace App\Enums;

enum TaskPriority: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
        };
    }

     public function getLabelBn(): string
    {
        return match ($this) {
            self::LOW => 'নিম্ন',
            self::MEDIUM => 'মধ্যম',
            self::HIGH => 'উচ্চ',
        };
    }
}
