<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class Student extends Model
{
    use HasFactory ,HasApiTokens, HasRoles;
    protected $table = 'student';
    protected $guard_name = 'web';
    protected $fillable = ['name', 'email', 'password', 'photo'];


    public function images(): HasMany {
        return $this->hasMany(Image::class, 'user_id', 'id');
    }

}
