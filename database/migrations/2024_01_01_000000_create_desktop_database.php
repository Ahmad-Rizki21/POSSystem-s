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
        // This migration is for desktop app initialization
        // Tables will be created by existing migrations
        Schema::create('desktop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('desktop_settings')->insert([
            ['key' => 'app_version', 'value' => '1.0.0'],
            ['key' => 'first_run', 'value' => 'true'],
            ['key' => 'backup_enabled', 'value' => 'true'],
            ['key' => 'backup_interval', 'value' => 'daily'],
            ['key' => 'last_backup', 'value' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desktop_settings');
    }
};