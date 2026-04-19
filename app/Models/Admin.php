<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;

    public static function findForForgotPassword(string $identifier): ?self
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            return null;
        }

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return static::query()
                ->whereRaw('LOWER(TRIM(email)) = ?', [mb_strtolower($identifier)])
                ->first();
        }

        return static::query()
            ->whereRaw('LOWER(TRIM(username)) = ?', [mb_strtolower($identifier)])
            ->first();
    }

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
