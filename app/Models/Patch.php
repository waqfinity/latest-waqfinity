<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use App\Traits\GlobalStatus;

class Patch extends Model
{
    use Searchable, GlobalStatus;
    use HasFactory;

    protected $casts = [
        'donation_ids' => 'array',
    ];
}
