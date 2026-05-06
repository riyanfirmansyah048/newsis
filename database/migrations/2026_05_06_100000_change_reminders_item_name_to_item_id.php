<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('reminders')) {
            return;
        }

        $addedItemIdColumn = false;

        if (Schema::hasColumn('reminders', 'item_name') && ! Schema::hasColumn('reminders', 'item_id')) {
            Schema::table('reminders', function (Blueprint $table) {
                $table->foreignId('item_id')->nullable()->after('id');
            });

            $addedItemIdColumn = true;
        }

        if (Schema::hasColumn('reminders', 'item_name') && Schema::hasColumn('reminders', 'item_id')) {
            DB::table('reminders')
                ->select('id', 'item_name')
                ->whereNull('item_id')
                ->orderBy('id')
                ->get()
                ->each(function (object $reminder): void {
                    $itemName = trim((string) $reminder->item_name);

                    if ($itemName === '') {
                        return;
                    }

                    $itemId = DB::table('items')
                        ->whereRaw('LOWER(name) = ?', [strtolower($itemName)])
                        ->value('id');

                    if (! $itemId) {
                        $itemId = DB::table('items')
                            ->where('name', 'like', "%{$itemName}%")
                            ->value('id');
                    }

                    if ($itemId) {
                        DB::table('reminders')
                            ->where('id', $reminder->id)
                            ->update(['item_id' => $itemId]);
                    }
                });
        }

        if ($addedItemIdColumn) {
            Schema::table('reminders', function (Blueprint $table) {
                $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('reminders', 'item_name')) {
            Schema::table('reminders', function (Blueprint $table) {
                $table->dropColumn('item_name');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('reminders')) {
            return;
        }

        if (! Schema::hasColumn('reminders', 'item_name')) {
            Schema::table('reminders', function (Blueprint $table) {
                $table->string('item_name')->nullable()->after('id');
            });
        }

        if (Schema::hasColumn('reminders', 'item_id') && Schema::hasColumn('reminders', 'item_name')) {
            DB::table('reminders')
                ->leftJoin('items', 'reminders.item_id', '=', 'items.id')
                ->whereNull('reminders.item_name')
                ->update([
                    'reminders.item_name' => DB::raw("COALESCE(items.name, '')"),
                ]);
        }

        if (Schema::hasColumn('reminders', 'item_id')) {
            Schema::table('reminders', function (Blueprint $table) {
                $table->dropForeign(['item_id']);
                $table->dropColumn('item_id');
            });
        }
    }
};
