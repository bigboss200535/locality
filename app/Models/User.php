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
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasUuids;

     // Status constants
    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const BLOCKED = 'Yes';
    
    // Archived constants
    const ARCHIVED_YES = 'Yes';
    const ARCHIVED_NO = 'No';

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
        'usage',
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
            'archived' => 'string', 
            'blocked' => 'string',
            'status' => 'string',
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


    /**
     * Check if user is active
     * Returns true if user is not blocked and not archived
     */
    public function isActive(): bool
    {
        return $this->blocked == self::STATUS_ACTIVE && $this->archived == self::ARCHIVED_NO;
    }

    /**
     * Check if user is blocked/disabled
     */
    public function isBlocked(): bool
    {
        return $this->blocked == self::BLOCKED;
    }
    /**
     * Check if user is archived
     */
    public function isArchived(): bool
    {
        return $this->archived == self::ARCHIVED_YES;
    }

    /**
     * Check if user can login
     */
    public function canLogin(): bool
    {
        return $this->isActive() && !$this->isBlocked() && !$this->isArchived();
    }

     /**
     * Get user status label for display
     */
    public function getStatusLabel(): string
    {
        if ($this->isBlocked()) {
            return 'Disabled';
        }
        if ($this->isArchived()) {
            return 'Archived';
        }
        if ($this->isActive()) {
            return 'Active';
        }
        return 'Unknown';
    }

    /**
     * Get user status badge color
     */
    public function getStatusBadge(): string
    {
        if ($this->isBlocked()) {
            return 'danger';
        }
        if ($this->isArchived()) {
            return 'warning';
        }
        if ($this->isActive()) {
            return 'success';
        }
        return 'secondary';
    }
}
