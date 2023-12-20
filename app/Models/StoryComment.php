<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StoryComment extends Model
{

    use GlobalStatus, Searchable;

    // blog
    public function story()
    {
        return $this->belongsTo(SuccessStory::class, 'success_story_id');
    }

    public function scopePublished($query) {
        return $query->where('status', Status::PUBLISHED);
    }
    public function scopeUnpublish($query) {
        return $query->where('status', Status::UNPUBLISH);
    }


    public function statusBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';
            if ($this->status == Status::PUBLISHED) {
                $html = '<span class="badge badge--success">' . trans("Published") . '</span>';
            } elseif ($this->status == Status::UNPUBLISH) {
                $html = '<span class="badge badge--danger">' . trans("Unpublished") . '</span>';
            }
            return $html;
        });
    }


}
