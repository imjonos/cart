<?php

use CodersStudio\Cart\Models\Purchase;
use CodersStudio\Cart\Models\PurchaseStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillPurchaseStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PurchaseStatus::insert([
            ['name' => 'pending'],
            ['name' => 'success'],
            ['name' => 'fail'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
