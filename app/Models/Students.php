<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'total_image',
        'sub_title',
    ];

    public function images()
    {
        return $this->hasMany(StudentsImages::class);
    }


}
