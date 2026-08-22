<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getInfoEmail($email)
    {
        return $this->where(['email'=>$email,'status'=>1])->first();
    }

    public function userRole()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function roles()
    {
        return $this->userRole();
    }

    public function can($permissions, $arguments = [])
    {
        if (is_string($permissions)) {
            $permissions = preg_split('/[|,]/', $permissions) ?: [];
        }

        $permissions = array_filter(array_map('trim', (array) $permissions));

        if ($permissions === []) {
            return false;
        }

        return $this->userRole()
            ->whereHas('permissionRole', function ($query) use ($permissions) {
                $query->whereIn('name', $permissions);
            })
            ->exists();
    }

    public function hasRoleName($roles): bool
    {
        $roles = is_string($roles)
            ? (preg_split('/[|,]/', $roles) ?: [])
            : (array) $roles;

        $roles = array_filter(array_map('trim', $roles));

        if ($roles === []) {
            return false;
        }

        return $this->userRole()
            ->whereIn('name', $roles)
            ->exists();
    }

    public $timestamps = true;
}
