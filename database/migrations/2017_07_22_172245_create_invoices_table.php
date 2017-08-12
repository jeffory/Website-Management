<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->date('date_issued');
            $table->text('note');
            $table->integer('days_until_due');
            $table->decimal('total', 11, 2)->default('0.00');
            $table->decimal('owing', 11, 2)->default('0.00');
            $table->boolean('old_invoice')->default(false);

            // Added in later migration...
            // $table->string('view_key', 20);
            $table->timestamps();
        });

        $database_type = Config::get("database.connections.{Config::get('database.default')}.driver");

        // Set the starting id if on MySQL
        if ($database_type == 'mysql') {
            DB::update('ALTER TABLE invoices AUTO_INCREMENT = 131');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
