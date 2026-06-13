<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // logo_solid   — for login page & Electron splash (image with solid/white background)
            // logo_transparent — for sidebar & invoices (image with transparent background)
            $table->string('logo_solid')->nullable()->after('site_logo');
            $table->string('logo_transparent')->nullable()->after('logo_solid');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['logo_solid', 'logo_transparent']);
        });
    }
};
