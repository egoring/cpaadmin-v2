<?php

namespace Tests\Unit;

use App\Support\SearchFilter;
use PHPUnit\Framework\TestCase;

class SearchFilterTest extends TestCase
{
    /** 종료일 당일이 포함되도록 다음 날을 경계로 돌려준다. */
    public function testEndBoundaryIsNextDay()
    {
        $this->assertSame('2026-09-19', SearchFilter::endBoundary('2026-09-18'));
    }

    /** 월·연 경계를 넘어가도 정확하다. */
    public function testEndBoundaryCrossesMonthAndYear()
    {
        $this->assertSame('2026-10-01', SearchFilter::endBoundary('2026-09-30'));
        $this->assertSame('2027-01-01', SearchFilter::endBoundary('2026-12-31'));
        $this->assertSame('2024-02-29', SearchFilter::endBoundary('2024-02-28'));
    }

    /** 경계는 종료일 당일의 마지막 시각보다 뒤에 있다. */
    public function testEndBoundaryCoversWholeDay()
    {
        $boundary = SearchFilter::endBoundary('2026-09-18');

        $this->assertTrue('2026-09-18 23:59:59' < $boundary);
        $this->assertFalse('2026-09-19 00:00:00' < $boundary);
    }

    /** 해석할 수 없는 값이면 경계를 만들지 않는다. */
    public function testEndBoundaryRejectsUnparsableValue()
    {
        $this->assertNull(SearchFilter::endBoundary(''));
        $this->assertNull(SearchFilter::endBoundary('   '));
        $this->assertNull(SearchFilter::endBoundary('not-a-date'));
    }

    /** 시작·종료일이 모두 있어야 기간 조회로 본다. */
    public function testHasRangeRequiresBothEnds()
    {
        $this->assertTrue(SearchFilter::hasRange('2026-09-01', '2026-09-18'));
        $this->assertFalse(SearchFilter::hasRange('2026-09-01', ''));
        $this->assertFalse(SearchFilter::hasRange('', '2026-09-18'));
        $this->assertFalse(SearchFilter::hasRange('  ', '  '));
        $this->assertFalse(SearchFilter::hasRange(null, null));
    }

    /** 정렬 방향은 asc/desc 만 받고 나머지는 기본값으로 떨어진다. */
    public function testSortDirectionWhitelist()
    {
        $this->assertSame('asc', SearchFilter::sortDirection('asc'));
        $this->assertSame('desc', SearchFilter::sortDirection('DESC'));
        $this->assertSame('desc', SearchFilter::sortDirection(''));
        $this->assertSame('desc', SearchFilter::sortDirection(null));
        $this->assertSame('desc', SearchFilter::sortDirection('msn; DROP TABLE member'));
    }

    /** 페이지당 행 수는 화면이 제공하는 값만 받는다. */
    public function testPerPageWhitelist()
    {
        $this->assertSame(15, SearchFilter::perPage(15));
        $this->assertSame(100, SearchFilter::perPage('100'));
        $this->assertSame(15, SearchFilter::perPage(0));
        $this->assertSame(15, SearchFilter::perPage(999999));
        $this->assertSame(15, SearchFilter::perPage('abc'));
        $this->assertSame(15, SearchFilter::perPage(null));
    }
}
