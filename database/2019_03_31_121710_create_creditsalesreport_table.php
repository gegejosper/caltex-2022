<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsalesreportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditsalesreports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cashierid');
            $table->string('branchid');
            $table->string('customerid');
            $table->string('invoicenum');
            $table->string('amount');
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
        Schema::dropIfExists('creditsalesreports');
    }
}
