<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;


class Student extends Model
{
    use HasFactory ,HasApiTokens;
    protected $table = 'student';
    protected $fillable = ['name', 'email', 'password', 'photo'];


    public function images(): HasMany {
        return $this->hasMany(Image::class, 'user_id', 'id');
    }

}
