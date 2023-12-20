<?php

namespace App\Models;


use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    use Searchable,GlobalStatus;

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get:fn () => $this->badgeData(),
        );
    }

    public function badgeData()
    {
        $html = '';
        if ($this->status == Status::PUBLISHED) {
            $html = '<span class="badge badge--success">' . trans("Publish") . '</span>';
        } elseif ($this->status == Status::PENDING) {
            $html = '<span class="badge badge--warning">' . trans("Pending") . '</span>';
        }
        return  $html;
    }
}
