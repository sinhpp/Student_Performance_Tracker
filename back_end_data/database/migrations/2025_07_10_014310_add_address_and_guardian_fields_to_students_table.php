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
        Schema::table('students', function (Blueprint $table) {
            $table->text('address')->nullable()->after('status');
            $table->string('guardian_name')->nullable()->after('address');
            $table->string('guardian_phone')->nullable()->after('guardian_name');
            $table->string('guardian_email')->nullable()->after('guardian_phone');
            $table->string('guardian_relation')->nullable()->after('guardian_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'guardian_name',
                'guardian_phone',
                'guardian_email',
                'guardian_relation'
            ]);
        });
    }
};
