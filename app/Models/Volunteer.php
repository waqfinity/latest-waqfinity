<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Volunteer extends Model
{
    use GlobalStatus, Searchable;

    protected $casts = [
        'address' => 'object',
    ];

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            function () {
                $html = '';
                if ($this->status == Status::VOLUNTEER_ACTIVE) {
                    $html = '<span class="badge badge--primary">' . trans("Active") . '</span>';
                } elseif ($this->status == Status::VOLUNTEER_INACTIVE) {
                    $html = '<span class="badge badge--danger">' . trans("Inactive") . '</span>';
                }

                return $html;
            }
        );
    }


}
