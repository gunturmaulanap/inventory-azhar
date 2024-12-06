<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deliveryGoods extends Model
{
    use HasFactory;

    protected $table = 'delivery_goods';
    protected $guarded = [];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
