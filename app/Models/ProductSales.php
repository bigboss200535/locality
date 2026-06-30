<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductSales extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;

    protected $table = 'product_sales';
    protected $primaryKey = 'sales_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'sales_id',
        'product_id',
        'payment_id',
        'receipt_number',
        'tenant_id',
        'store_id',
        'quantity',
        'unit_cost',
        'total',
        'transaction_time',
        'user_id',
        'added_date',
        'status',
        'added_by',
        'archived',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
