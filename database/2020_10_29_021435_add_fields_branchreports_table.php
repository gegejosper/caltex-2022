<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsBranchreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branchreports', function (Blueprint $table) {
            //
            $table->string('cash');
            $table->string('credit');
            $table->string('total_sales');
            $table->string('discount');
            $table->string('petty_voucher');
            $table->string('star_card');
            $table->string('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branchreports', function (Blueprint $table) {
            //
        });
    }
}
