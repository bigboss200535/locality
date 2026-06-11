<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogs extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'user_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

    protected $fillable = [
        'log_id',
        'user_id',
        'logname',
        'login_date',
        'logout_date', 
        'login_time',
        'logout_time',
        'user_pc', 
        'tenant_id', 
        'store_id', 
        'status', 
        'added_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_date'
    ];
}
