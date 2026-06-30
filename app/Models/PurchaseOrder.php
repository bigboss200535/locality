<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseOrder extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'purchase_order';
    protected $primaryKey = 'purchase_order_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

     protected $fillable = [
        'purchase_order_id',
        'order_no',
        'order_date',
        'invoice_no',
        'total_value',
        'discount',
        'vat',
        'supplier_id',
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
        return $this->hasOne(Stores::class, 'store_id', 'store_id');
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetails::class, 'purchase_order_id', 'purchase_order_id');
    }
}
