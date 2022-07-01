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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->integer('id');
            $table->string('nomor_invoice');
            $table->date('tanggal');
            $table->integer('pasien_id');
            $table->string('metode_pembayaran');
            $table->double('total', 20, 2);
            $table->string('bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('no_transaksi')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('pembayaran');
    }
};
