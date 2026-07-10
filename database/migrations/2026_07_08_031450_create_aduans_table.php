<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aduans', function (Blueprint $table) {
            $table->id();
            $table->string('kanal');
            $table->string('klasifikasi');
            $table->string('nama_akun')->nullable();
            $table->text('isi_aduan');
            $table->string('caption')->nullable();
            $table->date('tanggal_aduan');
            $table->time('waktu_aduan')->nullable();
            $table->boolean('sudah_direspon')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Tambahkan INDEX untuk mempercepat proses filter & statistik
            $table->index('tanggal_aduan');
            $table->index('kanal');
            $table->index('klasifikasi');
            $table->index('sudah_direspon');
            $table->index('created_by');
        });
        
        // Gunakan MEDIUMBLOB agar bisa menyimpan file gambar binary hingga 16MB tanpa base64 overhead
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE aduans ADD screenshot MEDIUMBLOB NULL AFTER waktu_aduan");
    }

    public function down(): void
    {
        Schema::dropIfExists('aduans');
    }
};
