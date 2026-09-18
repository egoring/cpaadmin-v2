# cpaadmin_v2

CPA 광고 유입 관리 백오피스.

외부 광고 랜딩페이지에서 발생한 문의를 수집해 적재하고, 광고주·매체·관리자 등급에 따라 나눠 보여주며, 기간·매체별로 조회해 엑셀·CSV로 내보냅니다.

---

## 요구 사항

| 항목 | 버전 |
|---|---|
| **PHP** | **^7.2** (Laravel 6.2 기준 7.2 ~ 7.4) |
| **Laravel** | **6.2.0** (LTS) |
| 데이터베이스 | MySQL 5.7+ / MariaDB |
| 프런트 빌드 | Node.js + npm (Laravel Mix) |

## 패키지

| 패키지 | 버전 | 용도 |
|---|---|---|
| `laravel/framework` | v6.2.0 | 프레임워크 |
| `maatwebsite/excel` | ~3.1.0 | 엑셀·CSV 내보내기 |
| `realrashid/sweet-alert` | ^2.0 | 알림 UI |
| `fideloper/proxy` | ^4.0 | 프록시 뒤 실제 접속 IP 확인 |
| `laravel/tinker` | ^1.0 | REPL |

---

## 동작 흐름

```
외부 광고 랜딩페이지
   │
   │  POST /crm/member          유입 전용 엔드포인트 (인증 없음, CSRF 예외)
   ▼
MemberController@store
   ├─ member          이름 · 전화 · 접속 IP · 매체코드 · 유입 URL · User-Agent
   └─ admanager_log   midx · idx · member_sn · referer · cppID
   │
   └─▶ 허용 호스트 목록에 있는 랜딩페이지로만 되돌려보냄
   ▼
[member 가드 로그인]
   /list                        목록 · 검색(매체코드/담당자/이름/전화) · 기간 필터 · 페이지당 행 수
   /list/exceldown · /csvdown   현재 검색 조건 그대로 엑셀 · CSV 내보내기
   /user                        계정 생성 · 승인 상태 변경 · 비밀번호 변경
   /event                       캠페인 등록 · 조회
```

### 권한 등급

| grade | 조회 범위 | 관리 |
|:--:|---|---|
| 1 | 본인에게 배정된 리드 (`member.idx`) | — |
| 2 | 하위 리드 (`member.midx`) | — |
| 9 | 전체 | 계정 생성 · 승인 상태 변경 · 비밀번호 초기화 · 광고주 필터 |

---

## 구조

```
app/
├── Http/Controllers/Admin/
│   ├── MemberController.php    유입 수집 · 목록 · 검색 · 내보내기 · 삭제
│   ├── UserController.php      계정 생성 · 승인 상태 · 비밀번호
│   └── EventController.php     캠페인 등록 · 조회
├── Http/Controllers/Auth/      로그인 (member 가드)
├── Http/Middleware/
│   ├── Member.php              member 가드 인증 확인
│   └── VerifyCsrfToken.php     유입 엔드포인트만 예외
├── Exports/
│   └── memberExport.php        검색 조건을 그대로 받아 엑셀·CSV 생성
├── Support/
│   ├── RefererGuard.php        유입 후 리다이렉트 허용 호스트 판정
│   └── SearchFilter.php        기간 경계 · 정렬 방향 · 페이지 크기 계산
└── User.php · Member.php · Event.php · Account.php

config/landing.php              유입 폼 리다이렉트 허용 호스트
routes/web.php                  관리 라우트 (member 미들웨어 그룹)
resources/views/member/         목록 · 계정 · 캠페인 화면 (Blade)
database/migrations/            전체 스키마
```

---

## 데이터베이스

`php artisan migrate` 로 전체 스키마가 생성됩니다.

### member — 광고 유입 리드

| 컬럼 | 타입 | 비고 |
|---|---|---|
| `msn` | bigint unsigned | PK, auto_increment |
| `event_code` | bigint | 이벤트코드, 기본 0 |
| `midx` / `idx` | int | 매체 계정 id / 광고주 계정 id |
| `bName` `bPhone` `bTel` | varchar(30) | 이름 · 연락처 |
| `bsex` `bbirth` `bconfirm` `chk_adult` | varchar(20) | 부가 항목 |
| `bAddr` | varchar(200) | 기본 `'0'` |
| `bIp` | varchar(30) | 접속 IP |
| `media_code` | varchar(255) | 매체코드 |
| `ccode` | varchar(255) | 캠페인코드 |
| `location_url` | varchar(300) | 유입 URL, 인덱스 |
| `agent` | varchar(255) | User-Agent |
| `etc` `bmemo` `remarks` | text | 문의 내용 · 메모 |
| `inputDate` | varchar(20) | 유입 일시 `Y-m-d H:i:s` |
| `v_status` | int | `0` 활성 · `4` 비활성 |
| `option1~3` | varchar(30) utf8mb4 | 확장 항목 |

인덱스: `member_index1(event_code, midx, idx, msn)` · `location_url(location_url)`

### admanager_log — 유입 호출 로그

| 컬럼 | 타입 |
|---|---|
| `sn` | bigint unsigned PK |
| `midx` / `idx` | int |
| `member_sn` | bigint unsigned → `member.msn` |
| `referer` `agent` `data_path` | varchar(255) |
| `date_time` | varchar(20) |
| `cppID` | varchar(100) |

### event — 캠페인

| 컬럼 | 타입 | 비고 |
|---|---|---|
| `esn` | bigint unsigned | PK |
| `advertiser_id` / `media_id` | int | 광고주 · 매체 계정 id |
| `event_name` `event_url` | varchar(100) | |
| `page_url` | text | 호출 CPA 페이지 URL |
| `result_id` / `result_page` | int / varchar(255) | 결과 페이지 연동 |
| `reg_date` | varchar(20) | |
| `adconfirm` | int | 기본 `2` |

### users — 계정

Laravel 기본 컬럼에 `username`(회사명) · `grade`(등급) · `tel` · `adconfirm`(`1` OFF / `2` ON)을 더해 사용합니다.

---

## 보안

| 항목 | 처리 |
|---|---|
| CSRF | 전 라우트에서 검증. 외부 유입 엔드포인트(`crm/member`)만 예외 |
| 계정 생성 | 관리자 등급만 호출 가능하고, 부여 가능한 등급은 화이트리스트(`1, 2, 9`)로 제한 |
| 비밀번호 변경 | 본인 변경은 현재 비밀번호 확인, 타인 초기화는 관리자만. 변경은 POST 전용 |
| 리드 내보내기 | 인증된 라우트에서만 접근. 등급별 조회 범위가 쿼리에 그대로 적용 |
| 유입 후 리다이렉트 | `config/landing.php` 허용 호스트의 URL만 통과 |
| 계정 목록 | 광고주 필터·비밀번호 화면의 계정 목록은 관리자에게만 노출 |
| 캠페인 등록 | 관리자가 아니면 `advertiser_id`가 본인 계정으로 고정 |
| 조회 파라미터 | 정렬 방향·페이지 크기는 화이트리스트, 검색어·날짜는 바인딩 파라미터 |
| 출력 | 모든 Blade 출력이 이스케이프(`{{ }}`) |
| 접속 IP | `TrustProxies`를 거친 실제 IP를 기록 |

---

## 테스트

```bash
vendor/bin/phpunit
```

조회 규칙과 리다이렉트 판정은 단위 테스트로 고정돼 있습니다. 데이터베이스 없이 실행됩니다.

| 대상 | 다루는 것 |
|---|---|
| `RefererGuard` | 허용 호스트·서브도메인 통과 / 유사 도메인(`notexample.com`, `example.com.evil.com`) 차단 / `http`·`https` 외 스킴 차단 / 허용 목록이 비면 전부 차단 |
| `SearchFilter` | 종료일 당일을 포함하는 기간 경계(월·연·윤년 넘김 포함) / 해석 불가 값 처리 / 정렬 방향·페이지 크기 화이트리스트 |

테스트 16건 · 단언 39건.

## 설치

```bash
composer install

cp .env.example .env
php artisan key:generate

# .env 에 DB 접속 정보와 허용 호스트를 채웁니다
#   LANDING_ALLOWED_HOSTS=example.com,landing.example.com

php artisan migrate

npm install && npm run dev
php artisan serve
```

### 환경 변수

| 키 | 설명 |
|---|---|
| `LANDING_ALLOWED_HOSTS` | 유입 폼 처리 후 되돌려보낼 랜딩페이지 호스트(쉼표 구분). 서브도메인 포함. 비워두면 외부로 리다이렉트하지 않습니다 |

---

## 라이선스

MIT
