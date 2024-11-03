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
        Schema::create('social_medias', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 255); // Change user_id to varchar(255)
            $table->string('facebookurl')->nullable();
            $table->string('instagramurl')->nullable();
            $table->string('youtubeurl')->nullable();
            $table->string('twitterurl')->nullable();
            $table->string('linkedinurl')->nullable();
            $table->timestamps();
    
            // If you want to set up a foreign key relationship, make sure the related field is also a string
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_media');
    }
};
