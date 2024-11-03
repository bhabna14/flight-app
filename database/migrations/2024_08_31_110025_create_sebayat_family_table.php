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
        Schema::create('sebayat_family', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('fathername')->nullable();
            $table->string('fatherphoto')->nullable();
            $table->string('mothername')->nullable();
            $table->string('motherphoto')->nullable();
            $table->enum('marital', ['married', 'unmarried'])->default('unmarried');
            $table->string('spouse')->nullable();
            $table->string('spousephoto')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sebayat_family');
    }
};
