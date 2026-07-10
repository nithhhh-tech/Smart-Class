<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('temp_threshold', 4, 1)->default(30.0);
            $table->integer('motion_timeout')->default(900); // 900 seconds = 15 minutes
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['temp_threshold', 'motion_timeout']);
        });
    }
};
