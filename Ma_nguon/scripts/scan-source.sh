#!/usr/bin/env bash
set -u

target_dir="${1:-locales}"
total=0
pass=0
fail=0

if [[ ! -d "$target_dir" ]]; then
  echo "Error: Thư mục '$target_dir' không tồn tại." >&2
  exit 2
fi

found_files=0
while IFS= read -r -d '' file; do
  found_files=$((found_files + 1))
  total=$((total + 1))
  
  if php -r "json_decode(file_get_contents('$file')); if (json_last_error() === JSON_ERROR_NONE) { exit(0); } else { exit(1); }" 2>/dev/null; then
    printf 'PASS translation file: %s\n' "$file"
    pass=$((pass + 1))
  else
    printf 'FAIL syntax error in translation file: %s\n' "$file" >&2
    fail=$((fail + 1))
  fi
done < <(find "$target_dir" -type f \( -name "*.json" -o -name "*.php" \) -print0)

if [[ "$found_files" -eq 0 ]]; then
  echo "Error: Không tìm thấy tệp bản dịch nào trong '$target_dir'." >&2
  exit 2
fi

echo "TOTAL=$total PASS=$pass FAIL=$fail"

if [[ "$fail" -gt 0 ]]; then
  exit 1
fi
exit 0
