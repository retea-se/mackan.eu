# Readme-länk Fix - Sammanfattning

## 🎯 Problem
- Manuella readme-länkar i verktygsfiler som inte alltid stämde
- Länkar fanns på olika platser (i headern, i titeln, etc.)
- Inkonsekvent hantering av readme-filer

## ✅ Lösning

### 1. Skapad funktion: `includes/find-readme.php`
- Funktionen `findReadmeFile()` söker automatiskt efter `readme.php` i samma mapp som verktyget
- Specialfall: `impex_map.php` använder `impex_map_help.php`
- Returnerar web-sökväg till readme-filen om den finns, annars `null`

### 2. Uppdaterad header: `includes/header.php`
- Readme-länken visas nu automatiskt i headern bredvid temaväxlaren
- Länken visas endast om readme-filen finns
- Konsistent placering och styling för alla verktyg

### 3. Tagna bort manuella readme-länkar
Följande filer har uppdaterats:
- `tools/koordinat/index.php`
- `tools/koordinat/impex.php`
- `tools/koordinat/impex_map.php`
- `tools/qr_v2/index.php`
- `tools/passwordgenerator/index.php`
- `tools/aptus/index.php`
- `tools/bolagsverket/index.php`
- `tools/css2json/index.php`
- `tools/csv2json/index.php`
- `tools/pts/index.php`
- `tools/stotta/index.php`
- `tools/testid/index.php`
- `tools/tfngen/index.php`
- `tools/kortlank/skapa-lank.php`
- `tools/kortlank/skapa.php`
- `tools/qr_v3/index.php`
- `tools/tts/index.php`
- `tools/mall_verktyg.php`

## 📋 Verktyg med readme.php (visar länk)
- ✅ `tools/aptus/readme.php`
- ✅ `tools/bolagsverket/readme.php`
- ✅ `tools/converter/readme.php`
- ✅ `tools/css2json/readme.php`
- ✅ `tools/csv2json/readme.php`
- ✅ `tools/kortlank/readme.php`
- ✅ `tools/passwordgenerator/readme.php`
- ✅ `tools/pts/readme.php`
- ✅ `tools/qr_v1/readme.php`
- ✅ `tools/qr_v2/readme.php`
- ✅ `tools/skyddad/readme.php`
- ✅ `tools/stotta/readme.php`
- ✅ `tools/testdata/readme.php`
- ✅ `tools/testid/readme.php`
- ✅ `tools/tfngen/readme.php`

## 📋 Verktyg utan readme.php (ingen länk)
- ❌ `tools/addy/` - Ingen readme
- ❌ `tools/koordinat/` - Har README.md men ingen readme.php
- ❌ `tools/qr_v3/` - Ingen readme
- ❌ `tools/rka/` - Har a2_readme.php men ingen readme.php i root
- ❌ `tools/tts/` - Ingen readme

## 🔍 Specialfall
- `tools/koordinat/impex_map.php` - Använder `impex_map_help.php` istället för `readme.php`

## ✅ Resultat
- ✅ En enda readme-länk per verktyg (i headern)
- ✅ Länken visas endast om readme-filen finns
- ✅ Konsistent placering (bredvid temaväxlaren)
- ✅ Automatisk upptäckt av readme-filer
- ✅ Ingen manuell hantering behövs i verktygsfiler

## 🎨 Styling
Readme-länken använder:
- Ikon: `fa-circle-info` (Font Awesome)
- CSS-klass: `knapp__ikon`
- Placering: Bredvid temaväxlaren i headern
- Tooltip: "Om verktyget"

## 📝 Nästa steg (valfritt)
Om verktyg utan readme ska ha dokumentation:
1. Skapa `readme.php` i verktygets mapp
2. Länken visas automatiskt i headern

