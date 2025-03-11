<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('facebook_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('facebook_id');
        });
    }
};
