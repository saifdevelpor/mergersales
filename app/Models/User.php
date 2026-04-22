<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected string $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'father_name',
        'id_card',
        'phone_number',
        'profile_photo',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $user): void {
            if (! Schema::hasTable('roles') || ! $user->role) {
                return;
            }

            $legacyRole = match (strtolower((string) $user->role)) {
                'admin' => 'admin',
                'seo_manager' => 'seo_manager',
                default => null,
            };

            if ($legacyRole) {
                $user->syncRoles([$legacyRole]);
            }
        });
    }

    public function messages()
    {
        return $this->hasMany(ChMessage::class, 'from_id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
