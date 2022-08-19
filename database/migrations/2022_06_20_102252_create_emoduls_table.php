<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmodulsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emoduls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 125);
            $table->string('slug', 255);
            $table->string('deskripsi', 255);
            $table->string('dosen', 50);
            $table->integer('prodi_id')->unsigned()->index();
            $table->integer('matakuliah_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('prodi_id')->references('id')->on('prodis')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('matakuliah_id')->references('id')->on('matakuliahs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emoduls');
    }
}
