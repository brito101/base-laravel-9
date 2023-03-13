<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo', 100)->nullable();
            $table->string('telephone')->nullable();
            $table->string('cell')->nullable();
            /** pattern */
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
            $table->dropColumn('telephone');
            $table->dropColumn('cell');
            $table->dropColumn('deleted_at');
        });
    }
};
