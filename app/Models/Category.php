<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use App\Traits\GlobalStatus;

class Category extends Model
{
    use Searchable, GlobalStatus;

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function story()
    {
        return $this->hasMany(SuccessStory::class);
    }

    public function scopeHasCampaigns($query)
    {
        return $query->whereHas('campaigns', function ($q) {
            $q->active()->running()->inComplete()->whereDate('deadline', '>', now());
        });
    }
}
