# Deployment Guide - Fixa 404-fel

## 🎯 Syfte
Denna guide hjälper dig att deploya filer som ger 404-fel på servern men finns lokalt.

## 📋 Fil-lista för deployment

### Admin-sidor
```
admin/pro-analytics.php
admin/security-monitor.php
admin/geo-country.php (uppdaterad - fixar 400-fel)
```

### Koordinat-verktyg
```
tools/koordinat/index.php
tools/koordinat/impex.php
tools/koordinat/impex_map.php
```

### QR-verktyg
```
tools/qr_v3/index.php
```

## 🚀 Deployment-steg

### Metod 1: FTP/SFTP

1. **Anslut till servern via FTP/SFTP**
   - Använd ditt FTP-klient (FileZilla, WinSCP, etc.)
   - Anslut till din server

2. **Navigera till rätt mappar**
   ```
   /public_html/admin/          (eller motsvarande)
   /public_html/tools/koordinat/
   /public_html/tools/qr_v3/
   ```

3. **Ladda upp filerna**
   - Dra och släpp filerna från lokalt till servern
   - Se till att filerna hamnar på rätt plats

4. **Verifiera filrättigheter**
   - Filerna bör ha rättigheter: **644** (rw-r--r--)
   - Mappar bör ha rättigheter: **755** (rwxr-xr-x)

### Metod 2: SSH/SCP

```bash
# Anslut till servern
ssh användare@servern

# Navigera till site-root
cd /path/to/site

# Verifiera att mappar finns
ls -la admin/
ls -la tools/koordinat/
ls -la tools/qr_v3/

# Ladda upp filer via SCP (från lokal maskin)
scp admin/pro-analytics.php användare@servern:/path/to/site/admin/
scp admin/security-monitor.php användare@servern:/path/to/site/admin/
scp admin/geo-country.php användare@servern:/path/to/site/admin/
scp tools/koordinat/index.php användare@servern:/path/to/site/tools/koordinat/
scp tools/koordinat/impex.php användare@servern:/path/to/site/tools/koordinat/
scp tools/koordinat/impex_map.php användare@servern:/path/to/site/tools/koordinat/
scp tools/qr_v3/index.php användare@servern:/path/to/site/tools/qr_v3/

# Sätt korrekta rättigheter
chmod 644 admin/pro-analytics.php
chmod 644 admin/security-monitor.php
chmod 644 admin/geo-country.php
chmod 644 tools/koordinat/index.php
chmod 644 tools/koordinat/impex.php
chmod 644 tools/koordinat/impex_map.php
chmod 644 tools/qr_v3/index.php
```

### Metod 3: Git Deployment (om du använder Git)

```bash
# På servern
cd /path/to/site
git pull origin main  # eller din branch

# Verifiera att filerna finns
ls -la admin/pro-analytics.php
ls -la tools/koordinat/index.php
```

## ✅ Verifiering efter deployment

### 1. Kontrollera att filerna finns
```bash
# Via SSH
ls -la /path/to/site/admin/pro-analytics.php
ls -la /path/to/site/admin/security-monitor.php
ls -la /path/to/site/tools/koordinat/index.php
ls -la /path/to/site/tools/koordinat/impex.php
ls -la /path/to/site/tools/koordinat/impex_map.php
ls -la /path/to/site/tools/qr_v3/index.php
```

### 2. Testa URL:erna
Efter deployment, testa dessa URL:er:
- https://mackan.eu/admin/pro-analytics.php
- https://mackan.eu/admin/security-monitor.php
- https://mackan.eu/admin/geo-country.php
- https://mackan.eu/tools/koordinat/
- https://mackan.eu/tools/koordinat/impex.php
- https://mackan.eu/tools/koordinat/impex_map.php
- https://mackan.eu/tools/qr_v3/

### 3. Kör länkkontroll igen
```bash
php check_links.php
```

## 🔧 Felsökning

### Problem: Filerna finns men ger fortfarande 404

1. **Kontrollera filrättigheter**
   ```bash
   chmod 644 filnamn.php
   ```

2. **Kontrollera webbserver-konfiguration**
   - Verifiera att PHP är aktiverat
   - Kontrollera om det finns .htaccess-regler som blockerar

3. **Kontrollera serverloggar**
   ```bash
   tail -f /var/log/apache2/error.log  # Apache
   tail -f /var/log/nginx/error.log    # Nginx
   ```

4. **Kontrollera PHP-fel**
   ```bash
   tail -f /var/log/php_errors.log
   ```

### Problem: 500 Internal Server Error

1. **Kontrollera PHP-syntax**
   ```bash
   php -l filnamn.php
   ```

2. **Kontrollera include-sökvägar**
   - Verifiera att `includes/`-mappen finns
   - Kontrollera att sökvägar är korrekta

3. **Aktivera felvisning temporärt**
   ```php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```

### Problem: 403 Forbidden

1. **Kontrollera filrättigheter**
   ```bash
   chmod 644 filnamn.php
   ```

2. **Kontrollera mapprättigheter**
   ```bash
   chmod 755 mappnamn/
   ```

3. **Kontrollera .htaccess**
   - Se om det finns regler som blockerar åtkomst

## 📝 Checklista

- [ ] Alla filer är uppladdade till rätt mappar
- [ ] Filrättigheter är korrekta (644)
- [ ] Mapprättigheter är korrekta (755)
- [ ] URL:erna testas och fungerar
- [ ] Inga PHP-syntaxfel
- [ ] Include-sökvägar är korrekta
- [ ] Serverloggar kontrolleras för fel

## 🎉 Efter deployment

När alla filer är deployade och verifierade:

1. ✅ Kör `php check_links.php` igen
2. ✅ Verifiera att alla länkar fungerar
3. ✅ Uppdatera dokumentation om nödvändigt
4. ✅ Ta bort temporära debug-inställningar

## 📞 Ytterligare hjälp

Om problemen kvarstår efter deployment:
1. Kontrollera serverloggar
2. Kontrollera webbserver-konfiguration
3. Kontakta hosting-providern om nödvändigt

