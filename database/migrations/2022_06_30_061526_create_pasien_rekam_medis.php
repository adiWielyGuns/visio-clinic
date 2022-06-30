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
        Schema::create('pasien_rekam_medis', function (Blueprint $table) {
            $table->integer('pasien_id');
            $table->integer('id');
            $table->string('id_rekam_medis');
            $table->date('tanggal');
            $table->integer('dokter_id');
            $table->text('tindakan');
            $table->text('keterangan');
            $table->string('created_by');
            $table->string('updated_by');
            $table->primary(['pasien_id', 'id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pasien_rekam_medis');
    }
};
