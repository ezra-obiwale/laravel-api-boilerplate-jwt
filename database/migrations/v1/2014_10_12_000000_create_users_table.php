<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.t
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('username', 15)->nullable();
            $table->string('photo')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('api_token')->nullable();
            $table->boolean('verified')->default(false);
            $table->string('token')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->integer('opinions')->nullable()->default(0);
            $table->integer('expressions')->nullable()->default(0);
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
