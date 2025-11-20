# 🛡️ T4 WordPress Plugin SAST Scanner
**Semgrep 기반 커스텀 WordPress 취약점 분석 자동화 도구**
![js](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

본 프로젝트는 WordPress 플러그인에서 자주 발생하는 보안 취약점을
정적 분석(SAST) 방식으로 자동 탐지하는 Github Actions 기반 자동화 스캐너입니다.

✔ WordPress 환경 맞춤형 Custom Semgrep Rules (SQLi, XSS, RCE, CSRF, Upload 등)		<br/>
✔ Semgrep 공식 Pack + Custom Rules 동시 사용		<br/>
✔ 플러그인 업로드 후 git push만 하면 자동 스캔		<br/>
✔ Summary / Severity / File 기반 상세 보고		<br/>
✔ SARIF(Code Scanning) + JSON(자동 가공) + Raw Debug 아티팩트 저장		<br/> <br/> <br/>

## 📁 프로젝트 구조
T4_WP_SASTScan/<br/>
 ├─ .github/workflows/semgrep.yml             # Github Actions 자동 스캔<br/>
 ├─ .semgrep/<br/>
 │    ├─ packs-only.yml                       # Semgrep 공식 Pack 설정<br/>
 │    ├─ local-rules.yml                      # WordPress Custom Rule Set<br/>
 └─ src/wp_plugins/                           # WordPress 플러그인 넣는 폴더<br/>

**플러그인 스캔 대상은 src/wp_plugins/ 아래에 존재하는 모든 .php 파일입니다.** <br/> <br/> <br/>

## 🚀 사용 방법
### 1) 스캔할 WordPress 플러그인을 src/wp_plugins/ 내에 추가
src/wp_plugins/<br/>
 ├─ pluginA/<br/>
 ├─ pluginB/<br/>
 └─ my-custom-plugin/<br/> <br/>


### 2) GitHub push
git add . <br/>
git commit -m "scan plugins" <br/>
git push <br/> <br/>

→ Github Actions가 자동으로 Semgrep 스캔 실행<br/>
→ Summary + SARIF + JSON 보고서 생성 <br/>
 <br/>


## 🔧 Semgrep 분석 Pipeline

본 프로젝트는 다음 두 종류의 룰셋을 동시에 실행한다. <br/>


## 📦 1. Semgrep Official Packs (packs-only.yml)

아래 공식 보안 Packs 포함:<br/>
p/ci<br/>
p/security-audit<br/>
p/secrets<br/>
p/php<br/>
p/javascript<br/>

→ WordPress 플러그인 내 일반 PHP 보안 취약점 전반 검사<br/>
→ SQLi, Command Injection, XSS, SSRF, 정보노출 등 광범위한 탐지 <br/>
 <br/>


## 🛠️ 2. WP Custom Rules (local-rules.yml)

WordPress 특화 취약점을 깊게 검사하는 커스텀 룰셋. <br/>
 <br/>


### 🔥 포함된 Custom Rules 목록
**1) SQL Injection (SQLi)**<br/>
$wpdb->query(), get_results() 등에<br/>
사용자 입력이 sanitize 없이 전달되는지 Taint 분석

**2) XSS**<br/>
echo, print, printf에 사용자 입력이 출력되는 흐름 추적<br/>
WP sanitizer (esc_html, esc_url 등) 우회 여부 탐지

**3) Command Injection (RCE)** <br/>
system(), exec(), `cmd` 등<br/>
PHP 시스템 명령에 사용자 입력이 들어가는 경우 경고

**4) Dangerous File Upload (CWE-434)** <br/>
move_uploaded_file() 직접 사용 <br/>
파일 확장자 체크 누락 <br/>
업로드 파일이 webroot에 저장되는 패턴 탐지 <br/>

**5) CSRF Nonce Missing**
admin_post_*, wp_ajax_* 콜백 함수에서 <br/>
update_option, delete_option 등을 수행하면서 Nonce 미체크 탐지

**6) REST API 권한 설정 누락**
permission_callback => '__return_true' <br/>
또는 callback 직접 전달 패턴 (register_rest_route) <br/>

**7) Unsafe unserialize()**
GET/POST/COOKIE 값이 unserialize()로 전달되는 패턴 <br/>
 <br/>


### 📝 GitHub Actions Summary Output 예시
## 🧪 Semgrep Summary
- Total findings: 12 <br/>
  - ERROR: 4 <br/>
  - WARNING: 5 <br/>
  - INFO: 3 <br/>

### 🔥 Critical WP Vulnerabilities (SQLi, XSS, RCE)
| ERROR | wp-sqli-taint-basic | my-plugin/file.php:31 | SQLi risk... | <br/>
| ERROR | wp-command-injection-taint | admin.php:12 | Possible RCE... | <br/>
| ERROR | wp-xss-taint-to-output | view.php:88 | Unescaped output... | <br/>

### 📂 Findings grouped by file
#### File: src/wp_plugins/pluginA/admin/save.php
| ERROR | wp-sqli... | 12 | SQL Injection risk | <br/>
| WARNING | wp-file-upload-move-raw | 55 | Dangerous upload | <br/>

#### File: src/wp_plugins/pluginB/ajax.php
| ERROR | wp-xss... | 33 | XSS risk |
 <br/>
 <br/>

### 📡 Output Artifacts
스캔 후 다음 결과들이 자동 업로드 <br/> <br/>

reports/semgrep.json	전체 취약점 원본 JSON <br/>
reports/semgrep.sarif	GitHub Code Scanning 용 포맷 <br/>
reports/upload.json	WordPress Upload rule 별도 결과 <br/>
`reports/debug_out/*.out	err` <br/>

**→ GitHub UI에서도 Code Scanning Alerts 로 실시간 확인 가능**
 <br/>
 <br/>

## 🤖 Workflow 내부 동작 요약

UTF-8 BOM 정리 <br/>
local + official pack 병렬 실행 <br/>
JSON → Summary 자동 변환 <br/>
Severity별/파일별 정리 <br/>
SARIF 업로드 <br/>
Debug 아티팩트 저장 <br/>
