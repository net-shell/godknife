<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'username',
        'email',
        'email_verified_at',
        'description',
        'thumbnail',
        'profile',
        'gender',
        'relationship',
        'partner',
        'school',
        'college',
        'university',
        'work',
        'website',
        'location',
        'address',
        'is_private',
        'is_banned',
        'expiration_date',
        'banned_at',
        'banned_to',
        'password',
        'facebook_id',
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

    public function is_friend()
    {
        return Friend::where(['user_id' => $this->id])->orWhere('friend_id', $this->id)->first()->status ?? '';
    }

    public function scopeInAdmins($query)
    {
        $admins = config('godknife.admins', []);
        return $query->whereIn('username', $admins);
    }

    public function scopeNotInAdmins($query)
    {
        $admins = config('godknife.admins', []);
        return $query->whereNotIn('username', $admins);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getIsAdminAttribute()
    {
        $admins = config('godknife.admins', []);
        return in_array($this->username, $admins);
    }
}
