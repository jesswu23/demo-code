<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_lists', function (Blueprint $table) {
            $table->enum('status', [1, 2, 3])->after('user_id')->default('1')->comment('1:申請中、2:許可、3:拒絕');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_lists', function (Blueprint $table) {
            //
        });
    }
};
