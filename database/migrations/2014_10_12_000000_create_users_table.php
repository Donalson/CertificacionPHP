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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Nombre',30);
            $table->string('Apellido',50);
            $table->string('Categoria',15);
            $table->string('Telefono',9);
            $table->string('DPI',15)->nullable();
            $table->string('Foto')->nullable();
            $table->string('Fnacimiento',10);
            $table->string('username',20)->unique();
            $table->string('password');
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
