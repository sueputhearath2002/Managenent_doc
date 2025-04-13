<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            Schema::table('attendance', function (Blueprint $table) {
                $table->date('attendanceDate')->after('reason');
                $table->time('attendanceTime')->after('attendanceDate');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropColumn(['attendanceDate', 'attendanceTime']);
        });
    }
};
