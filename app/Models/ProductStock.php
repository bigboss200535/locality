<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductStock extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'product_stocked';
    protected $primaryKey = 'stock_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

     protected $fillable = [
        'stock_id',
        'product_id',
        'stock_quantity',
        'batch_number',
        'stock_date',
        'stocked_by', 
        'expiry_date', 
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

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'store_id');
    }
}
