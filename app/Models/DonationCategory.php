<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalStatus;

class DonationCategory extends Model
{
   
    use Searchable, GlobalStatus;
    protected $table = "donation_category";
    
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
    
}
