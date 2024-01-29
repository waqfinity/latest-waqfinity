<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use App\Traits\GlobalStatus;
use App\Models\Property;
use App\Models\Patch;

class Investment extends Model
{
    use Searchable, GlobalStatus;
    use HasFactory;

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function patch()
    {
        return $this->belongsTo(Patch::class, 'patch_id');
    }
}
