name: Semgrep (with PDF report)

on:
  push:
    branches: [ main, master, develop ]
    paths:
      - 'src/wp_plugins/**'
      - '.semgrep/**'
      - '.semgrep.yml'
      - '.github/workflows/semgrep.yml'
  pull_request:
    types: [opened, synchronize, reopened]
    branches: [ main, master, develop ]
    paths:
      - 'src/wp_plugins/**'
      - '.semgrep/**'
      - '.semgrep.yml'

permissions:
  contents: read
  security-events: write
  pull-requests: write

jobs:
  semgrep:
    runs-on: ubuntu-latest
    timeout-minutes: 25
    env:
      SEMGREP_APP_TOKEN: ${{ secrets.SEMGREP_APP_TOKEN }}

    steps:
      - name: Checkout
        uses: actions/checkout@v4
        with: { fetch-depth: 0 }

      - name: Ensure Python (for PDF)
        uses: actions/setup-python@v5
        with: { python-version: '3.x' }

      - name: Install deps for PDF
        run: |
          python -m pip install --upgrade pip
          pip install reportlab

      - name: List semgrep configs
        run: |
          ls -la .semgrep || true
          test -f .semgrep/.semgrep.yml && sed -n '1,120p' .semgrep/.semgrep.yml || true

      # --- Semgrep (Cloud mode) ---
      - name: Run Semgrep via Semgrep Cloud
        if: env.SEMGREP_APP_TOKEN != ''
        uses: semgrep/semgrep-action@v1
        with:
          config: ".semgrep/.semgrep.yml"
          generateSarif: "1"
          sarifFile: semgrep.sarif
          comment: true
          include: |
            src/wp_plugins/**
          exclude: |
            node_modules
            vendor
            .git
            .github
        continue-on-error: true   # ❗️실패해도 다음 단계 진행

      # --- Semgrep (CLI fallback) ---
      - name: Install Semgrep (CLI)
        if: env.SEMGREP_APP_TOKEN == ''
        run: pip install semgrep

      - name: Run Semgrep (CLI -> SARIF)
        if: env.SEMGREP_APP_TOKEN == ''
        run: |
          CONFIG=".semgrep/.semgrep.yml"
          if [ ! -f "$CONFIG" ]; then CONFIG="auto"; fi
          semgrep --config "$CONFIG" \
            --include 'src/wp_plugins/**' \
            --exclude node_modules --exclude vendor --exclude .git --exclude .github \
            --sarif --output semgrep.sarif || true   # ❗️어떤 코드여도 계속

      # --- PDF 생성 스텝 ---
      - name: Generate PDF report from SARIF
        run: |
          python scripts/sarif_to_pdf.py semgrep.sarif "Semgrep-Report.pdf" || python - <<'PY'
          # 백업: sarif 파일 없을 때 빈 보고서라도 생성
          from reportlab.lib.pagesizes import A4
          from reportlab.pdfgen import canvas
          c = canvas.Canvas("Semgrep-Report.pdf", pagesize=A4)
          c.setTitle("Semgrep Report")
          c.drawString(50, 800, "Semgrep Report")
          c.drawString(50, 780, "No SARIF produced. (scan failed or no results output)")
          c.save()
          PY

      # --- PDF/원본 결과 업로드 ---
      - name: Upload artifacts (PDF & SARIF)
        uses: actions/upload-artifact@v4
        with:
          name: semgrep-report
          path: |
            Semgrep-Report.pdf
            semgrep.sarif
          if-no-files-found: warn
