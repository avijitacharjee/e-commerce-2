<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
            id
            name
            username
            email
            email_verified_at
            email_verification_token
            phone
            gender
            dob
            is_active
            status
            password
            picture
        */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('gender', 8)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('status', 15)->default('inactive');
            $table->string('password', 128)->nullable();
            $table->string('picture_path')->nullable();

            $table->string('email', 64)->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token', 128)->nullable();

            $table->string('phone_number', 15)->nullable()->unique();
            $table->timestamp('number_verified_at')->nullable();
            $table->string('number_verification_pin', 128)->nullable();

            $table->string('facebook_id', 28)->nullable()->unique();
            $table->string('google_id', 28)->nullable()->unique();
            
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
