<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'admin_role'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['password' => 'hashed'];

    const ROLE_ADMIN = 'admin';
    const ROLE_USER  = 'user';

    // Admin sub-roles mapped to category names
    const ADMIN_ROLES = [
        'super_admin'       => null,           // sees ALL categories
        'finance'           => 'Financial',
        'hr'                => 'Staff Conduct',
        'academic'          => 'Academic',
        'facilities'        => 'Facilities',
        'it'                => 'IT / Technology',
        'accommodation'     => 'Accommodation',
        'other'             => 'Other',
    ];

    public function isAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
    public function isUser(): bool  { return $this->role === self::ROLE_USER; }

    public function isSuperAdmin(): bool
    {
        return $this->isAdmin() && (is_null($this->admin_role) || $this->admin_role === 'super_admin');
    }

    // Get the category name this admin is restricted to (null = no restriction)
    public function adminCategory(): ?string
    {
        if (!$this->isAdmin() || $this->isSuperAdmin()) return null;
        return self::ADMIN_ROLES[$this->admin_role] ?? null;
    }

    // Human-readable admin role label
    public function adminRoleLabel(): string
    {
        if ($this->isSuperAdmin()) return 'Super Admin';
        return ucfirst(str_replace('_', ' ', $this->admin_role ?? 'Super Admin'));
    }

    public function complaints() { return $this->hasMany(Complaint::class); }
    public function comments()   { return $this->hasMany(Comment::class); }
}
