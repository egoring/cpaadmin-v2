<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 캠페인(이벤트) 테이블.
 *
 * 기본키는 `esn` 이며, 목록 정렬에 사용한다.
 */
class CreateEventTable extends Migration
{
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->bigIncrements('esn')->comment('일련번호');

            $table->integer('advertiser_id')->default(0)->comment('광고주 계정 id');
            $table->integer('media_id')->default(0)->comment('매체 계정 id');

            $table->string('event_name', 100)->nullable();
            $table->string('event_url', 100)->nullable();
            $table->text('page_url')->nullable()->comment('호출 CPA 페이지 URL');
            $table->string('reg_date', 20)->nullable();

            $table->integer('result_id')->nullable()->default(0)->comment('0: not link, 1: result page call');
            $table->string('result_page', 255)->nullable()->comment('결과 페이지 URL');
            $table->integer('adconfirm')->nullable()->default(2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event');
    }
}
