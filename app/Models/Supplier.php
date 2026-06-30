<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Supplier extends Model
{
   use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

     protected $fillable = [
        'supplier_id',
        'supplier_name',
        'telephone',
        'email',
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

    
}
