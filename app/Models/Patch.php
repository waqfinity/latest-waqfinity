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

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    protected $casts = [
        'donation_ids' => 'array',
    ];
}
