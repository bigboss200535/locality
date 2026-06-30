<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductRequisition extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;

    protected $table = 'requisition';
    protected $primaryKey = 'requisition_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'requisition_id',
        'requisition_no',
        'order_store_id',
        'issue_store_id',
        'requisition_date',
        'unit_price',
        'quantity',
        'total_value',
        'product_id',
        'tenant_id',
        'requsition_status',
        'comments',
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

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'store_id');
    }

    public function orderStore()
    {
        return $this->belongsTo(Stores::class, 'order_store_id', 'store_id');
    }

    public function issueStore()
    {
        return $this->belongsTo(Stores::class, 'issue_store_id', 'store_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }
}
