<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_batch_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('production_batch_expenses', 'reference')) {
                $table->string('reference')->nullable()->after('id');
            }

            if (!Schema::hasColumn('production_batch_expenses', 'date')) {
                $table->date('date')->nullable()->after('production_batch_id');
            }

            if (!Schema::hasColumn('production_batch_expenses', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('note');
            }
        });

        $counter = 1;
        DB::table('production_batch_expenses')
            ->leftJoin('production_batches', 'production_batches.id', '=', 'production_batch_expenses.production_batch_id')
            ->orderBy('production_batch_expenses.id')
            ->select([
                'production_batch_expenses.id',
                'production_batch_expenses.reference',
                'production_batch_expenses.created_at',
                'production_batches.date as batch_date',
                'production_batches.user_id as batch_user_id',
            ])
            ->get()
            ->each(function ($expense) use (&$counter) {
                DB::table('production_batch_expenses')
                    ->where('id', $expense->id)
                    ->update([
                        'reference' => $expense->reference ?: make_reference_id('CE', $counter),
                        'date' => $expense->batch_date ?: ($expense->created_at ? substr((string) $expense->created_at, 0, 10) : now()->toDateString()),
                        'user_id' => $expense->batch_user_id,
                    ]);

                $counter++;
            });
    }

    public function down(): void
    {
        Schema::table('production_batch_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('production_batch_expenses', 'user_id')) {
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('production_batch_expenses', 'date')) {
                $table->dropColumn('date');
            }

            if (Schema::hasColumn('production_batch_expenses', 'reference')) {
                $table->dropColumn('reference');
            }
        });
    }
};
