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
            $table->string('profile_image_path')->nullable();
            $table->text('profile_image_base64')->nullable();
            $table->string('profile_image_mime_type')->nullable();
            $table->timestamp('profile_image_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['profile_image_path', 'profile_image_base64', 'profile_image_mime_type', 'profile_image_updated_at']);
        });
    }
};
