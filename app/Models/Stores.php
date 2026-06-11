<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'stores';
    protected $primaryKey = 'store_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

     protected $fillable = [
        'store_id',
        'tenant_id',
        'store_name',
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
}
