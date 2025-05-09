<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendance';
    protected $fillable = [
        'id',
        'studentId',
        'status',
        'reason',
        "attendanceDate",
        "attendanceTime",
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentId');
    }


}
