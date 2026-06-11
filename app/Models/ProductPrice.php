<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_prices';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

    protected $fillable = [
        'product_id',
        'unit_cost',
        'unit_price',
        'tenant_id',
        'store_id',
        'user_id',
        'added_date',
        'updated_date',
        'status',
        'added_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
