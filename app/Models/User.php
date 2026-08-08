<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'phone',
        'address',
        'city',
        'country',
        'role',
        'designation',
        'password',
        'is_admin',
    ];

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
        ];
    }

    /**
     * Get the user's avatar URL with fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar) && file_exists(public_path(ltrim($this->avatar, '/')))) {
            return $this->avatar;
        }

        // Generate UI Avatars SVG/Image URL based on user's name
        $name = urlencode($this->name ?: 'Admin');
        return "https://ui-avatars.com/api/?name={$name}&background=4338ca&color=ffffff&bold=true&size=128";
    }
}
