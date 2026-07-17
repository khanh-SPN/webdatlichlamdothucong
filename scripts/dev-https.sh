#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CERT_DIR="$ROOT_DIR/config/dev-certs"

command -v mkcert >/dev/null 2>&1 || {
  echo "mkcert is required. Install with: brew install mkcert nss"
  exit 1
}

command -v caddy >/dev/null 2>&1 || {
  echo "caddy is required. Install with: brew install caddy"
  exit 1
}

mkdir -p "$CERT_DIR"

if [[ ! -f "$CERT_DIR/localhost.pem" || ! -f "$CERT_DIR/localhost-key.pem" ]]; then
  echo "Generating local TLS certs into $CERT_DIR"
  mkcert -install
  mkcert -cert-file "$CERT_DIR/localhost.pem" -key-file "$CERT_DIR/localhost-key.pem" localhost 127.0.0.1 ::1
fi

echo "Starting HTTPS proxy at https://localhost:8766 (Cake should run on http://localhost:8765)"
exec caddy run --config "$ROOT_DIR/Caddyfile"

