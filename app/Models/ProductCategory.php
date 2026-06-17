<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

    protected $fillable = [
        'category_id',
        'category_name',
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

     public function store()
    {
        return $this->hasOne(Stores::class, 'store_id', 'store_id');
    }

     public function tenant()
    {
        return $this->hasOne(Tenant::class, 'tenant_id', 'tenant_id');
    }
}
