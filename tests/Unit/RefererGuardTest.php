<?php

namespace Tests\Unit;

use App\Support\RefererGuard;
use PHPUnit\Framework\TestCase;

class RefererGuardTest extends TestCase
{
    private function guard()
    {
        return new RefererGuard(['example.com', 'Landing.co.kr']);
    }

    /** 허용 호스트의 주소는 그대로 통과한다. */
    public function testAllowsExactHost()
    {
        $url = 'http://example.com/event/a?media=naver';

        $this->assertSame($url, $this->guard()->sanitize($url));
    }

    /** 서브도메인도 함께 허용한다. */
    public function testAllowsSubdomain()
    {
        $url = 'https://ad.landing.co.kr/form';

        $this->assertSame($url, $this->guard()->sanitize($url));
    }

    /** 허용 목록에 없는 호스트는 막는다. */
    public function testBlocksUnknownHost()
    {
        $this->assertNull($this->guard()->sanitize('http://evil.com/phish'));
    }

    /** 허용 호스트를 접미사로 흉내 낸 주소는 막는다. */
    public function testBlocksSuffixLookalike()
    {
        $this->assertNull($this->guard()->sanitize('http://notexample.com/'));
        $this->assertNull($this->guard()->sanitize('http://example.com.evil.com/'));
    }

    /** 대소문자와 끝점은 구분하지 않는다. */
    public function testHostComparisonIsCaseInsensitive()
    {
        $this->assertTrue($this->guard()->allows('EXAMPLE.COM'));
        $this->assertTrue($this->guard()->allows('AD.Example.com.'));
    }

    /** http/https 가 아닌 스킴은 막는다. */
    public function testBlocksNonHttpScheme()
    {
        $this->assertNull($this->guard()->sanitize('javascript:alert(1)'));
        $this->assertNull($this->guard()->sanitize('ftp://example.com/x'));
    }

    /** 빈 값과 호스트 없는 주소는 막는다. */
    public function testBlocksEmptyAndHostlessUrl()
    {
        $this->assertNull($this->guard()->sanitize(null));
        $this->assertNull($this->guard()->sanitize('   '));
        $this->assertNull($this->guard()->sanitize('/event/a'));
    }

    /** 허용 목록이 비어 있으면 아무 주소도 통과하지 않는다. */
    public function testEmptyAllowListBlocksEverything()
    {
        $guard = new RefererGuard([]);

        $this->assertNull($guard->sanitize('http://example.com/'));
    }

    /** 설정값의 공백과 빈 항목은 무시한다. */
    public function testIgnoresBlankEntries()
    {
        $guard = new RefererGuard(['  example.com  ', '', '   ']);

        $this->assertSame('http://example.com/a', $guard->sanitize('http://example.com/a'));
    }
}
