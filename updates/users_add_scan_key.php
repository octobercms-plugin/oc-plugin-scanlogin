<?php namespace Jcc\Scanlogin\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UsersAddScanKey extends Migration
{
    public function up()
    {
        if(!Schema::hasColumn('users', 'openid')){
            Schema::table('users', function ($table) {
                $table->string('openid')->nullable();
            });
        }
        if(!Schema::hasColumn('users', 'scan_key')){
            Schema::table('users', function ($table) {
                $table->string('scan_key')->nullable();
            });
        }

    }

    public function down()
    {
        if (Schema::hasColumn('users', 'openid')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('openid');
            });
        }
        if (Schema::hasColumn('users', 'scan_key')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('scan_key');
            });
        }
    }
}
