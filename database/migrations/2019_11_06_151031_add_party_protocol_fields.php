<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartyProtocolFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->integer('ballots_valid')->nullable();
            $table->integer('ballots_changed')->nullable();
            $table->integer('ballots_unchanged')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->dropColumn(['ballots_unchanged', 'ballots_changed', 'ballots_valid']);
        });
    }
}
