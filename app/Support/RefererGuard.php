<?php

namespace App\Support;

/**
 * 유입 폼 처리 후 되돌려보낼 주소를 검사한다.
 *
 * 랜딩페이지가 넘겨준 referer 를 그대로 믿으면 임의의 외부 주소로 보낼 수 있으므로,
 * 허용 호스트 목록에 있는 주소만 통과시킨다. 서브도메인은 함께 허용한다.
 */
class RefererGuard
{
    /** @var string[] */
    private $allowedHosts;

    public function __construct(array $allowedHosts = [])
    {
        $this->allowedHosts = array_values(array_filter(array_map(function ($h) {
            return strtolower(trim((string) $h));
        }, $allowedHosts), function ($h) {
            return $h !== '';
        }));
    }

    /** 설정 파일의 허용 호스트로 생성한다. */
    public static function fromConfig()
    {
        return new static((array) config('landing.allowed_hosts', []));
    }

    /**
     * 허용된 주소면 그대로, 아니면 null 을 돌려준다.
     *
     * @param  string|null  $url
     * @return string|null
     */
    public function sanitize($url)
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if ($scheme !== null && ! in_array(strtolower($scheme), ['http', 'https'], true)) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (! $host) {
            return null;
        }

        return $this->allows($host) ? $url : null;
    }

    /** 호스트가 허용 목록에 해당하는지 (자기 자신 또는 서브도메인). */
    public function allows($host)
    {
        $host = strtolower(rtrim((string) $host, '.'));
        if ($host === '') {
            return false;
        }

        foreach ($this->allowedHosts as $allowed) {
            if ($host === $allowed) {
                return true;
            }
            if (substr($host, -(strlen($allowed) + 1)) === '.' . $allowed) {
                return true;
            }
        }

        return false;
    }
}
