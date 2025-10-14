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
        Schema::table('jawabans', function (Blueprint $table) {
            $table->foreignId('tugas_id')->nullable()->after('id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('siswa_id')->nullable()->after('tugas_id')->constrained('siswas')->onDelete('set null');
            $table->text('isi')->nullable()->after('siswa_id');
            $table->string('file')->nullable()->after('isi');
            $table->integer('nilai')->nullable()->after('file');
            $table->text('komentar')->nullable()->after('nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawabans', function (Blueprint $table) {
            $table->dropForeign(['tugas_id']);
            $table->dropColumn('tugas_id');
            $table->dropForeign(['siswa_id']);
            $table->dropColumn('siswa_id');
            $table->dropColumn(['isi', 'file', 'nilai', 'komentar']);
        });
    }
};
