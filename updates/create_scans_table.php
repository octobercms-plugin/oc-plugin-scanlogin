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
            $table->string('uuid')->nullable();
            $table->string('ticket')->nullable();
            $table->date('expired_at')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jcc_scanlogin_scans');
    }
}
