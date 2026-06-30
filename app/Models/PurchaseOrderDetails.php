<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseOrderDetails extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'purchase_order_details';
    protected $primaryKey = 'purchase_order_details_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

     protected $fillable = [
        'purchase_order_details_id',
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total',
        'order_date',
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

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'store_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'purchase_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
