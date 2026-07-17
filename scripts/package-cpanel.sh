#!/usr/bin/env bash
# Build a zip you can upload and extract into cPanel public_html (document root).
# Does not modify this repo's vendor/ (staging + composer --no-dev happens in a temp dir).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if ! command -v composer >/dev/null 2>&1; then
  echo "composer not found in PATH. Install Composer first." >&2
  exit 1
fi

STAMP="$(date +%Y%m%d-%H%M)"
STAGE="$(mktemp -d)"
trap 'rm -rf "${STAGE}"' EXIT

echo "Copying project to staging (excluding logs, cache, vendor, git, tests) ..."
rsync -a \
  --exclude='.git' \
  --exclude='logs' \
  --exclude='tmp/cache' \
  --exclude='tmp/sessions' \
  --exclude='vendor' \
  --exclude='*.sqlite' \
  --exclude='tests' \
  --exclude='.phpunit.cache' \
  --exclude='node_modules' \
  --exclude='.DS_Store' \
  --exclude='team112-cpanel-*.zip' \
  "${ROOT}/" "${STAGE}/"

mkdir -p "${STAGE}/logs"
mkdir -p "${STAGE}/tmp/cache/models"
mkdir -p "${STAGE}/tmp/cache/persistent"
mkdir -p "${STAGE}/tmp/cache/views"
mkdir -p "${STAGE}/tmp/sessions"
mkdir -p "${STAGE}/tmp/tests"

echo "Running composer install --no-dev in staging ..."
(
  cd "${STAGE}"
  composer install --no-dev --optimize-autoloader --no-interaction
)

OUT="${ROOT}/team112-cpanel-${STAMP}.zip"
(
  cd "${STAGE}"
  zip -rq "${OUT}" .
)

echo "Created: ${OUT}"
echo ""
echo "Next steps:"
echo "  1. In cPanel File Manager, open public_html and upload this zip, then Extract."
echo "  2. Copy config/.env.example to config/.env and set DATABASE_URL, SECURITY_SALT, DEBUG=false."
echo "  3. Set chmod 775 (or 755) on tmp/ and logs/ if the app cannot write cache/sessions."
