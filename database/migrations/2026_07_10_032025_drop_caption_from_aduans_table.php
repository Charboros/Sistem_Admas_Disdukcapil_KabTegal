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
        // Gabungkan caption ke isi_aduan untuk data lama
        \Illuminate\Support\Facades\DB::table('aduans')
            ->whereNotNull('caption')
            ->where('caption', '!=', '')
            ->update([
                'isi_aduan' => \Illuminate\Support\Facades\DB::raw("CONCAT(isi_aduan, '\n\nKeterangan/Caption Asli: ', caption)")
            ]);

        Schema::table('aduans', function (Blueprint $table) {
            $table->dropColumn('caption');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aduans', function (Blueprint $table) {
            $table->string('caption')->nullable()->after('isi_aduan');
        });
    }
};
