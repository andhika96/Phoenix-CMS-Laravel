<?php

namespace App\Enums;

enum QueryAcceptedComparatorEnum: string
{
    case EQUAL = '=';
    case NOTEQUAL = '!=';
    case LIKE = 'like';
    case GREATER = '>';
    case SMALLER = '<';
    
}
