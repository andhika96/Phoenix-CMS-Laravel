<?php

namespace App\Enums;

enum FormValidationAcceptedFileEnum: string
{
    case IMAGE = 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120';
}
