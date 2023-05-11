<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteHasLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_has_label', function (Blueprint $table) {
            $table->unsignedBigInteger('label_id')->unsigned();
            $table->foreign('label_id')->references('id')
                ->on('labels')->onDelete('cascade');
            $table->unsignedBigInteger('note_id')->unsigned();
            $table->foreign('note_id')->references('id')
                ->on('notes')->onDelete('cascade');
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
        Schema::dropIfExists('note_has_label');
    }
}
