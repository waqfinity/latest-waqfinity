<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Campaign extends Model
{
    use Searchable;

    protected $casts = [
        'proof_images' => 'object',
    ];

    public function campaignsDonationCategory()
    {
        return $this->hasMany(CampaignsDonationCategory::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function donation()
    {
        return $this->hasMany(Donation::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', Status::CAMPAIGN_APPROVED);
    }
    public function scopeRejected($query)
    {
        return $query->where('status', Status::REJECTED);
    }
    public function scopeSuccess($query)
    {
        return $query->where('status', Status::CAMPAIGN_APPROVED)->where('completed', Status::YES);
    }
    public function scopePending($query)
    {
        return $query->where('status', Status::PENDING)->where('is_extend', Status::NO);
    }
    public function scopeExtended($query)
    {
        return $query->where('status', Status::PENDING)->where('is_extend', Status::YES);
    }
    public function scopeCompleted($query)
    {
        return $query->where('completed', Status::YES);
    }
    public function scopeExpired($query)
    {
        return $query->whereDate('deadline', '<', now());
    }
    public function scopeRunning($query)
    {
        return $query->active()->where('stop', Status::NO)->where('completed', Status::NO)->whereDate('deadline', '>', now());
    }
    public function scopeInComplete($query)
    {
        return $query->where('completed', Status::NO);
    }
    public function scopeFilters($query, $filters)
    {
        if ($category = @$filters) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }
    }
    public function getAddressAttribute()
    {
        $address="";
        if(@$this->user->address->address){
            $address.=@$this->user->address->address;
        }
        if($this->user->address->city){
            $address.=", ".@$this->user->address->city;
        }
        if($this->user->address->state){
            $address.=", ".@$this->user->address->state;
        }
        if($this->user->address->zip){
            $address.=", ".@$this->user->address->zip;
        }
        if($this->user->address->country){
            $address.=", ".@$this->user->address->country;
        }
        return $address;
    }
    public function statusBadge(): Attribute
    {
        return new Attribute(
            function () {
                $html = '';
                if ($this->deadline < now()) {
                    $html = '<span class="badge badge--dark">' . trans("Expired") . '</span>';
                } elseif ($this->status == Status::CAMPAIGN_APPROVED) {
                    $html = '<span class="badge badge--primary">' . trans("Approved") . '</span>';
                } elseif ($this->status == Status::REJECTED) {
                    $html = '<span class="badge badge--danger">' . trans("Rejected") . '</span>';
                } elseif ($this->status == Status::PENDING) {
                    $html = '<span class="badge badge--warning">' . trans("Pending") . '</span>';
                }
                return $html;
            }
        );
    }
}
