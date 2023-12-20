<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use App\Traits\GlobalStatus;

class CampaignMeta extends Model
{
    use Searchable, GlobalStatus;

    protected $table = "wphm_postmeta";
}
