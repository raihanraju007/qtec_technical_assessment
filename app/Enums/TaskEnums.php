<?php

namespace App\Enums;

enum TaskEnums: int
{
    const int LIMIT_PER_PAGE = 10;

    const int TITLE_MAX_LENGTH = 255;

    const int DESCRIPTION_MAX_LENGTH = 2000;
}
