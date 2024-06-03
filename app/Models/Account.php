<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Account extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasApiTokens;

    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'fullname',
        'remember_token',
        'recovery_code',
        'recovery_code_duration',
        'token',
        'status'
    ];    

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        $user = $this->with('roles')->where('email', auth()->user()->email)->first();

        return $user->hasRole(['Administrator']);
    }

    public function checkRole(): bool
    {
        $user = $this->with('roles')->where('email', auth()->user()->email)->first();

        return $user->hasRole(['Administrator']);
    }

    public function checkPermission(): bool
    {
        $user = $this->with('roles')->where('email', auth()->user()->email)->first();

        return $user->hasRole(['Administrator']);
    }

    public function getRecoveryCodeDurationAttribute($value)
    {
        return Carbon::createFromTimestamp($value)->toDateTimeString();
    }

    public function setRecoveryCodeDurationAttribute($value)
    {
        $this->attributes['recovery_code_duration'] = Carbon::parse($value)->timestamp;
    }

    // public function getUpdatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d H:i:s');
    // }

    // public function setUpdatedAtAttribute($value)
    // {
    //     $this->attributes['updated_at'] = Carbon::createFromTimestamp($value)->toDateTimeString();
    // }

    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d H:i:s');
    // }

    // public function setCreatedAtAttribute($value)
    // {
    //     $this->attributes['created_at'] = Carbon::createFromTimestamp($value)->toDateTimeString();
    // }
}
