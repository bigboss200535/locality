<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

    protected $fillable = [
        'product_id',
        'product_name',
        'product_type',
        'category_id', 
        'tenant_id', 
        'store_id', 
        'user_id', 
        'expiry_date', 
        'added_date',
        'updated_date',
        'status', 
        'added_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_date'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'category_id');
    }

    public function price()
    {
        return $this->hasOne(ProductPrice::class, 'product_id', 'product_id');
    }

    public function stock()
    {
        return $this->hasOne(ProductStock::class, 'product_id', 'product_id');
    }
    
     public function store()
    {
        return $this->hasOne(Stores::class, 'store_id', 'store_id');
    }

     public function tenant()
    {
        return $this->hasOne(Tenant::class, 'tenant_id', 'tenant_id');
    }
}
