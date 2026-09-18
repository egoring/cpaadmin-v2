<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 유입 1건당 남기는 호출 로그. member 저장 직후 함께 기록된다.
 * 컬럼 구성은 운영 DB 스키마 문서와 동일하다.
 */
class CreateAdmanagerLogTable extends Migration
{
    public function up()
    {
        Schema::create('admanager_log', function (Blueprint $table) {
            $table->bigIncrements('sn');

            $table->integer('midx')->nullable();
            $table->integer('idx')->nullable();
            $table->unsignedBigInteger('member_sn')->nullable()->comment('member.msn');

            $table->string('referer', 255)->nullable();
            $table->string('agent', 255)->nullable();
            $table->string('data_path', 255)->nullable();
            $table->string('date_time', 20)->nullable();
            $table->string('cppID', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admanager_log');
    }
}
