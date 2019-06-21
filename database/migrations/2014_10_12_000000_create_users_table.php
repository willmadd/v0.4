<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->string('username')->unique();
            $table->string('name');
            $table->string('api');
            $table->string('iatacode')->nullable();
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('phone');
            $table->string('agencyname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(false);
            $table->string('activation_token');
            $table->string('signup_day');
            $table->string('signup_month');
            $table->string('signup_year');
            $table->string('access');
            $table->integer('requests')->default(0);
            $table->integer('limit')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
//braintree fields
            $table->string('braintree_id')->nullable();
            $table->string('paypal_email')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
