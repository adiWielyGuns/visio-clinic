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
        Schema::create('pasien', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('id_pasien');
            $table->string('name');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki', 'Perempuan']);
            $table->string('telp');
            $table->string('alamat');
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('pasien');
    }
};
