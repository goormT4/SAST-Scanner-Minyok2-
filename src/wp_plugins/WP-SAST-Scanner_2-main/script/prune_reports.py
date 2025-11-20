# scripts/prune_reports.py
import os, glob
keep = 20
files = sorted(glob.glob("reports/Semgrep-Report_*.pdf")) + \
        sorted(glob.glob("reports/semgrep_*.sarif"))
for path in files[:-keep]:
    try: os.remove(path)
    except: pass
