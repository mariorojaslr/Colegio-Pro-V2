<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'school_id',
        'is_active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function collegiate()
    {
        return $this->hasOne(Collegiate::class);
    }

    public function enrolledLessons()
    {
        return $this->belongsToMany(Lesson::class)->withPivot('status', 'paid_amount')->withTimestamps();
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function isOwner()
    {
        return strtoupper($this->role) === 'OWNER';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    /**
     * Verifica si el usuario tiene un permiso específico o acceso total.
     */
    public function hasPermission($permission)
    {
        if ($this->isOwner() || $this->role === 'ADMIN_COLEGIO' || $this->role === 'ADMIN_INTERNO') {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Verifica si el usuario tiene AL MENOS UN permiso de administración o es admin full.
     */
    public function hasAnyAdminPermission()
    {
        if ($this->isOwner() || $this->role === 'ADMIN_COLEGIO' || $this->role === 'ADMIN_INTERNO') {
            return true;
        }

        return !empty($this->permissions);
    }
}
