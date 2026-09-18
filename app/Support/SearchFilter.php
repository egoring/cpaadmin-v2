<?php

namespace App\Support;

/**
 * 목록 조회 파라미터를 다루는 값 계산.
 *
 * 화면과 내보내기가 같은 규칙을 써야 해서 한곳에 모았다.
 */
class SearchFilter
{
    const PER_PAGE_OPTIONS = [15, 30, 50, 100];
    const DEFAULT_PER_PAGE = 15;

    /** 정렬 방향은 asc/desc 만 허용한다. */
    public static function sortDirection($value, $default = 'desc')
    {
        $value = strtolower(trim((string) $value));

        return in_array($value, ['asc', 'desc'], true) ? $value : $default;
    }

    /** 페이지당 행 수는 화면이 제공하는 값만 허용한다. */
    public static function perPage($value, $default = self::DEFAULT_PER_PAGE)
    {
        $value = (int) $value;

        return in_array($value, self::PER_PAGE_OPTIONS, true) ? $value : $default;
    }

    /** 시작·종료일이 모두 채워져 있는지. */
    public static function hasRange($start, $end)
    {
        return trim((string) $start) !== '' && trim((string) $end) !== '';
    }

    /**
     * 기간 조회의 종료 경계.
     *
     * 저장된 값에 시각이 붙어 있으므로 종료일 당일을 포함하려면
     * '종료일 +1일 미만' 으로 비교해야 한다. 해석할 수 없는 값이면 null.
     *
     * @param  string  $endDate
     * @return string|null  'Y-m-d'
     */
    public static function endBoundary($endDate)
    {
        $endDate = trim((string) $endDate);
        if ($endDate === '') {
            return null;
        }

        $ts = strtotime($endDate . ' +1 day');

        return $ts === false ? null : date('Y-m-d', $ts);
    }
}
