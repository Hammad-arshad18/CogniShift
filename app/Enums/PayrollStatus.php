<?php

namespace App\Enums;

enum PayrollStatus: string
{
    case DRAFT = 'draft';
    case PROCESSING = 'processing';
    case PAID = 'paid';
    case FAILED = 'failed';
}
