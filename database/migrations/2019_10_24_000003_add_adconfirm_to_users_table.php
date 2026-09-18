<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 계정 승인 상태 컬럼.
 *
 * 관리자가 계정의 사용 여부를 전환할 때 쓰는 값이다. 기본값은 사용(2).
 */
class AddAdconfirmToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('adconfirm')->nullable()->default(2)->comment('1: OFF, 2: ON');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('adconfirm');
        });
    }
}
