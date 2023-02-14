<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean("enabled")->nullable()->default(true);
            $table->text('destination')->nullable();
            $table->string('style')->nullable();
            $table->enum('form_number', [1, 2, 3]);
            $table->foreignId("owner")
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->uuid('hash')->unique();
            $table->bigInteger('visits')->default(0);
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
        Schema::dropIfExists('forms');
    }
};
