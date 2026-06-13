<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // App title shown as the big heading on the splash screen   e.g. "NECI ERP"
            $table->string('app_title')->nullable()->after('company_name');
            // Tagline shown below the title on the splash screen        e.g. "NEC Super and Cables Industries"
            $table->string('app_tagline')->nullable()->after('app_title');
        });

        // Seed sensible defaults into the existing row so it is never empty
        DB::table('settings')->update([
            'app_title'   => 'Industrial ERP',
            'app_tagline' => 'Industrial ERP System',
        ]);
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['app_title', 'app_tagline']);
        });
    }
};
