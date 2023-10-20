<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('email_file_id')->after('file_id');
        });

        DB::statement('UPDATE emails SET email_file_id = file_id');
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('file_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('file_id')->after('email_file_id');
        });

        DB::statement('UPDATE emails SET file_id = email_file_id');
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('email_file_id');
        });
    }
};
