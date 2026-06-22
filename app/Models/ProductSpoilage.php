<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductSpoilage extends Model
{

    use HasFactory, Notifiable;
    use HasUuids;

    protected $table = 'product_management';
    protected $primaryKey = 'product_management_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

   protected $fillable = [
        'product_management_id',
        'product_id',
        'batch_id',
        'quantity',
        'unit_cost',
        'reason',
        'expiry_date',
        'tenant_id',
        'comments',
        'spoiled_date',
        'store_id',
        // 'transaction_time',
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
