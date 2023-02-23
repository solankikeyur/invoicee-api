<?php

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
        $customer = new Customer();
        $user = new User();
        Schema::create($customer->getTable(), function (Blueprint $table) use($user) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on($user->getTable());
            $table->string("name");
            $table->string("email")->nullable();
            $table->string("mobile")->nullable();
            $table->longText("address")->nullable();
            $table->tinyInteger("status")->default(1);
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
        $customer = new Customer();
        Schema::table($customer->getTable(), function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists($customer->getTable());
    }
};
