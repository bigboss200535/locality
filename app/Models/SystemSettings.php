<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\SystemSettingsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SystemSettings extends Model
{
    use HasFactory, Notifiable;
    use HasUuids;
    
    protected $table = 'system_settings';
    protected $primaryKey = 'setting_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

     protected $fillable = [
        'setting_id',
        'system_name',
        'company',
        'subscription_based',
        'telephone',
        'website',
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
}
