<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Role extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

     protected $fillable = [
        'role_id',
        'tenant_id',
        'role_name',
        'role_description',
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
}
