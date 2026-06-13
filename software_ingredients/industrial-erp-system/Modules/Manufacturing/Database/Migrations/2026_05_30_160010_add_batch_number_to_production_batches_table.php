<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('production_batches', 'batch_number')) {
            Schema::table('production_batches', function (Blueprint $table) {
                $table->unsignedBigInteger('batch_number')->nullable()->after('id');
            });
        }

        $counter = 1;
        DB::table('production_batches')
            ->orderBy('id')
            ->select(['id', 'reference'])
            ->get()
            ->each(function ($batch) use (&$counter) {
                DB::table('production_batches')
                    ->where('id', $batch->id)
                    ->update([
                        'batch_number' => $counter,
                        'reference' => $batch->reference ?: make_reference_id('PB', $counter),
                    ]);

                $counter++;
            });

        Schema::table('production_batches', function (Blueprint $table) {
            $table->unique('batch_number');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('production_batches', 'batch_number')) {
            Schema::table('production_batches', function (Blueprint $table) {
                $table->dropUnique(['batch_number']);
                $table->dropColumn('batch_number');
            });
        }
    }
};
