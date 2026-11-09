<?php

declare(strict_types=1);

namespace Versatecnologia\Cadsus\ValueObjects;

enum CadsusGender: string
{
    case MALE = 'M';
    case FEMALE = 'F';
    case UNDETERMINED = 'N';
}
