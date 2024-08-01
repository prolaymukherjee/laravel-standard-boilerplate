<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Ideator = 'ideator';
    case Investor = 'investor';
}
