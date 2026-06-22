<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasUuids;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'firstname',
        'othername', 
        'tenant_id',
        'store_id',
        'role_id',
        'blocked',
        'oldpassword',
        'provider', 
        'provider_id',
        'avatar',
        'last_login',
        'salt',
        'telephone',
        'telephone_verified',
        'telephone_verified_at',
        'mode',
        'email',
        'email_verified',
        'email_verified_at',
        'added_id',
        'added_date',
        'added_by',
        'udpated_by',
        'updated_by',
        'status',
        'archived',
        'archived_id',
        'archived_by',
        'archived_date'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'store_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
