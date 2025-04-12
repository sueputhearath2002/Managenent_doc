<?php

namespace Database\Seeders;

use App\Models\Student;
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
        // Define Permissions
        $student_view_personal_attendance = Permission::create(["name" => "student.viewListPersonalAttendance"]);
        $admin_view_list_student_attendance = Permission::create(["name" => "admin.viewListStudentAttendance"]);
        $admin_view_data_student_attendance = Permission::create(["name" => "admin.viewDataStudentAttendance"]); // Fixed duplicate
        $admin_upload_student_images = Permission::create(["name" => "admin.uploadImageStudent"]);
        $admin_update_attendance_student = Permission::create(["name" => "admin.updateAttendanceStudent"]);
        $admin_filter_date_attendance_student = Permission::create(["name" => "admin.filterDateAttendanceStudent"]);
        $admin_filter_month_attendance_student = Permission::create(["name" => "admin.filterMonthAttendanceStudent"]);
        $student_create_request = Permission::create(["name" => "student.createRequest"]);
        $student_filter_date_personal_attendance = Permission::create(["name" => "student.filterDatePersonalAttendance"]);
        $student_view_list_personal_attendance_month = Permission::create(["name" => "student.viewListAttendanceMonth"]);
        $student_view_data_personal_attendance_month = Permission::create(["name" => "student.viewDataAttendanceMonth"]);
        $student_filter_month_personal_attendance = Permission::create(["name" => "student.filterMonthPersonalAttendance"]);

        // Create and Assign Admin Role
        $role_admin = Role::create(["name" => "admin"]);
        $role_admin->givePermissionTo([
            $admin_view_list_student_attendance,
            $admin_view_data_student_attendance,
            $admin_upload_student_images,
            $admin_update_attendance_student,
            $admin_filter_date_attendance_student,
            $admin_filter_month_attendance_student,
        ]);

        // Create Admin User
        $admin = Student::create([
            "name" => "admin1",
            "email" => "admin@gmail.com",
            "password" => bcrypt("password"),
        ]);
        $admin->assignRole($role_admin);

        // Create and Assign Student Role
        $role_student = Role::create(["name" => "student"]);
        $role_student->givePermissionTo([
            $student_view_personal_attendance,
            $student_create_request,
            $student_filter_date_personal_attendance,
            $student_view_list_personal_attendance_month,
            $student_view_data_personal_attendance_month,
            $student_filter_month_personal_attendance,
        ]);

        // Create Student User
        $student = Student::create([

            "name" => "student",
            "email" => "student@gmail.com",
            "password" => bcrypt("password"),
        ]);
        $student->assignRole($role_student);
    }
}
