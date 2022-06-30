<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwal_dokter_log', function (Blueprint $table) {
            $table->string('no_reservasi');
            $table->string('telp')->nullable();
            $table->string('alamat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal_dokter_log', function (Blueprint $table) {
            $table->dropColumn(['no_reservasi', 'telp', 'alamat']);
        });
    }
};
