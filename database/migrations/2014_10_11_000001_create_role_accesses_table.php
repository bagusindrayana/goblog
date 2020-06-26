<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('access_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('RESTRICT')->onUpdate('RESTRICT');

            $table->index('access_id');
            $table->foreign('access_id')->references('id')->on('accesses')->onDelete('RESTRICT')->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_accesses');
    }
}
