<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 광고 유입 리드 테이블.
 *
 * 기본키는 `msn` 이며, 목록 정렬·삭제·내보내기에서 이 값을 사용한다.
 * member_index1 은 목록 기본 조회(매체·광고주별 최신순)를 받치는 복합 인덱스다.
 */
class CreateMemberTable extends Migration
{
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->bigIncrements('msn')->comment('일련번호');

            $table->bigInteger('event_code')->nullable()->default(0)->comment('이벤트코드');
            $table->integer('midx')->nullable()->comment('매체(상위) 계정 id');
            $table->integer('idx')->nullable()->comment('광고주 계정 id (users.id)');

            $table->string('bName', 30)->nullable();
            $table->string('bPhone', 30)->nullable();
            $table->string('bTel', 30)->nullable();
            $table->string('bsex', 20)->nullable();
            $table->string('bbirth', 20)->nullable();
            $table->string('bAddr', 200)->nullable()->default('0');
            $table->string('bIp', 30)->nullable();
            $table->string('bconfirm', 20)->nullable();
            $table->string('chk_adult', 20)->nullable();

            $table->string('media_code', 255)->nullable()->comment('매체코드');
            $table->text('etc')->nullable();
            $table->text('bmemo')->nullable();
            $table->text('remarks')->nullable();

            // 운영 DB에서 날짜가 DATETIME 이 아니라 varchar(20) 이다. 기존 데이터와 맞추기 위해 유지.
            $table->string('inputDate', 20)->nullable();

            $table->string('location_url', 300)->nullable();
            $table->integer('v_status')->nullable()->default(0)->comment('0: 활성, 4: 비활성');
            $table->string('agent', 255)->nullable();
            $table->string('ccode', 255)->nullable()->comment('캠페인코드');

            $table->string('option1', 30)->charset('utf8mb4')->nullable();
            $table->string('option2', 30)->charset('utf8mb4')->nullable();
            $table->string('option3', 30)->charset('utf8mb4')->nullable();

            $table->index(['event_code', 'midx', 'idx', 'msn'], 'member_index1');
            $table->index('location_url', 'location_url');
        });
    }

    public function down()
    {
        Schema::dropIfExists('member');
    }
}
