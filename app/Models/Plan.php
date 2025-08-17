<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['minPrice', 'maxPrice'];

    protected function casts()
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function base()
    {
        return $this->belongsTo(BasePlan::class, 'base_plan_id');
    }

    public function getMinPriceAttribute(): float
    {
        return (float) $this->zones()->min('price');
    }

    public function getMaxPriceAttribute(): float
    {
        return (float) $this->zones()->max('price');
    }
}
