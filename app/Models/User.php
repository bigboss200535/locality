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

// #[Fillable(['name', 'email', 'password'])]
// #[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasUuids;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing= false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
        'user_id',
        'username',
        'password',
        // 'fullname',
        'firstname',
        'othername', 
        // 'gender_id',
        'oldpassword',
        'provider', 
        'provider_id',
        'avatar',
        'last_login',
        'salt',
        // 'facility_id',
        'telephone',
        'telephone_verified',
        'telephone_verified_at',
        // 'user_roles_id',
        'mode',
        'email',
        'email_verified',
        'email_verified_at',
        'added_id',
        'added_date',
        'udpated_by',
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
}
