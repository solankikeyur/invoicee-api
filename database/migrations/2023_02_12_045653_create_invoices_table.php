<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $invoice = new Invoice();
        $customer = new Customer();
        $user = new User();

        Schema::create($invoice->getTable(), function (Blueprint $table) use($user, $customer) {
            $table->id();
            $table->unsignedBigInteger("customer_id")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on($user->getTable())->onDelete(NULL);
            $table->foreign("customer_id")->references("id")->on($customer->getTable())->onDelete(NULL);
            $table->string("customer_name")->nullable();
            $table->json("items")->nullable();
            $table->decimal("total_amount",10,2)->default(0);
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
        $invoice = new Invoice();
        Schema::table($invoice->getTable(), function(Blueprint $table) {
            $table->dropForeign(['user_id', 'customer_id']);
        });
        Schema::dropIfExists($invoice->getTable());
    }
};
