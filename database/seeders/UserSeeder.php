<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student_view_personal_attendance = Permission::create(["name" => "student.viewListPersonalAttendance"]);
        $student_create_request = Permission::create(["name" => "student.createRequest"]);
        $student_filter_date_personal_attendance = Permission::create(["name" => "student.filterDatePersonalAttendance"]);
        $student_view_list_personal_attendance_month = Permission::create(["name" => "student.viewListAttendanceMonth"]);
        $student_view_data_personal_attendance_month = Permission::create(["name" => "student.viewDataAttendanceMonth"]);
        $student_filter_month_personal_attendance = Permission::create(["name" => "student.filterMonthPersonalAttendance"]);

        // For admin role

        $role_admin = Role::create(["name" => "admin"]);

        $role_admin ->givePermissionTo([
            $student_view_personal_attendance,
            $student_create_request,
            $student_filter_date_personal_attendance,
            $student_view_list_personal_attendance_month,
            $student_view_data_personal_attendance_month,
            $student_filter_month_personal_attendance,
        ]);

        $admin = User::create([
            "name" => "admin",
            "email" => "admin@student.com",
            "password" => bcrypt("password"),
        ]);

        $admin->assignRole($role_admin);
        $admin ->givePermissionTo([
            $student_view_personal_attendance,
            $student_create_request,
            $student_filter_date_personal_attendance,
            $student_view_list_personal_attendance_month,
            $student_view_data_personal_attendance_month,
            $student_filter_month_personal_attendance,
        ]);


        $student = User::create([
            "name" => "student",
            "email" => "student@student.com",
            "password" => bcrypt("password"),
        ]);
        $role_student = Role::create(["name" => "student"]);
        
        $student->assignRole($role_student );
        $student ->givePermissionTo([
            $student_view_personal_attendance,
            $student_create_request,
            $student_filter_date_personal_attendance,
            $student_view_list_personal_attendance_month,
            $student_view_data_personal_attendance_month,
            $student_filter_month_personal_attendance,
        ]);

    }
}
