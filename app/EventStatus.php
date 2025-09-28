<?php

namespace App;

enum EventStatus: string
{
    case UPCOMING = 'UPCOMING';
    case ONGOING = 'ONGOING';
    case CANCELLED = 'CANCELLED';
    case COMPLETED = 'COMPLETED';
}
