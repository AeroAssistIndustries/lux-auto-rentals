#!/usr/bin/env bash
# Builds meridian-chrono-theme.zip from the current staging site.
# Usage: bash meridian-chrono/wordpress/build-wp-theme.sh
set -euo pipefail
here="$(cd "$(dirname "$0")" && pwd)"
site="$(dirname "$here")"
out="$here/dist"
stage="$(mktemp -d)/meridian-chrono"

mkdir -p "$stage" "$out"
cp "$here/theme/style.css" "$here/theme/index.php" "$here/theme/functions.php" "$here/theme/screenshot.png" "$stage/"
cp "$site/index.html" "$stage/app.html"
mkdir -p "$stage/logos" && cp "$site"/logos/*.svg "$stage/logos/"
cp "$here/INSTALL.md" "$stage/INSTALL.md"

for f in "$stage"/*.php; do php -l "$f" >/dev/null; done

rm -f "$out/meridian-chrono-theme.zip"
(cd "$(dirname "$stage")" && zip -qr -X "$out/meridian-chrono-theme.zip" meridian-chrono)
echo "Built $out/meridian-chrono-theme.zip"
