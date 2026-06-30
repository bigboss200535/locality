<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Stores extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'stores';
    protected $primaryKey = 'store_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'store_id',
        'tenant_id',
        'store_name',
        'telephone',
        'store_code',
        'store_description',
        'type_of_business', 
        'added_date',
        'updated_date',
        'status', 
        'added_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_date'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    // Relationship with User
    public function users()
    {
        return $this->hasMany(User::class, 'store_id', 'store_id');
    }
}
