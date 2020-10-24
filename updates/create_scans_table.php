<?php namespace Jcc\Scanlogin\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateScansTable extends Migration
{
    public function up()
    {
        Schema::create('jcc_scanlogin_scans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('uuid')->nullable();
            $table->string('ticket')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('start_use_at')->nullable();
            $table->boolean('is_use')->default(false);
            $table->string('ip_address')->nullable();
            $table->string('login_type')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jcc_scanlogin_scans');
    }
}
