<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Farmer = 'farmer';
    case Buyer = 'buyer';
}
