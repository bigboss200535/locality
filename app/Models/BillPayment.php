<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BillPayment extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'bills_payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

    protected $fillable = [
        'payment_id',
        'total_payment',
        'service_payment',
        'product_payment', 
        'total_levies',
        'receipt_number',
        'subtotal',
        'item_discount',
        'cart_discount',
        'cart_discount_type',
        'cart_discount_value',
        'total_discount',
        'transaction_time',
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
}
