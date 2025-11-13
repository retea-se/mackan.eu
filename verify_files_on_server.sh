#!/bin/bash
# verify_files_on_server.sh
# Skript för att verifiera att filerna finns på servern
# Användning: ./verify_files_on_server.sh [sökväg-till-site-root]

SITE_ROOT="${1:-/path/to/site}"

echo "=========================================="
echo "Verifierar filer på servern"
echo "Sökväg: $SITE_ROOT"
echo "=========================================="
echo ""

# Färger för output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Funktion för att kontrollera fil
check_file() {
    local file_path="$1"
    local full_path="$SITE_ROOT$file_path"

    if [ -f "$full_path" ]; then
        local perms=$(stat -c "%a" "$full_path" 2>/dev/null || stat -f "%OLp" "$full_path" 2>/dev/null)
        local size=$(stat -c "%s" "$full_path" 2>/dev/null || stat -f "%z" "$full_path" 2>/dev/null)
        echo -e "${GREEN}✅ EXISTS${NC}: $file_path"
        echo "   Rättigheter: $perms | Storlek: $size bytes"
        return 0
    else
        echo -e "${RED}❌ MISSING${NC}: $file_path"
        return 1
    fi
}

# Kontrollera admin-filer
echo "📁 Admin-filer:"
check_file "/admin/pro-analytics.php"
check_file "/admin/security-monitor.php"
check_file "/admin/geo-country.php"
echo ""

# Kontrollera koordinat-filer
echo "🗺️  Koordinat-verktyg:"
check_file "/tools/koordinat/index.php"
check_file "/tools/koordinat/impex.php"
check_file "/tools/koordinat/impex_map.php"
echo ""

# Kontrollera QR-verktyg
echo "📱 QR-verktyg:"
check_file "/tools/qr_v3/index.php"
echo ""

# Kontrollera includes
echo "📦 Includes (för referens):"
check_file "/includes/layout-start.php"
check_file "/includes/layout-end.php"
echo ""

echo "=========================================="
echo "Kontroll klar!"
echo ""
echo "Om filer saknas, deploya dem från lokalt repo."
echo "Om filer finns men ger 404, kontrollera:"
echo "  - Filrättigheter (bör vara 644)"
echo "  - Webbserver-konfiguration"
echo "  - Serverloggar"
echo "=========================================="


