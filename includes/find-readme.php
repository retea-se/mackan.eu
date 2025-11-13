<?php
/**
 * find-readme.php - Hittar readme-fil för aktuellt verktyg
 *
 * Denna funktion söker efter readme.php (eller impex_map_help.php för impex_map)
 * i samma mapp som den aktuella filen.
 * Returnerar web-sökväg till readme-filen om den finns, annars null.
 *
 * @return string|null Web-sökväg till readme-filen eller null om den inte finns
 */
function findReadmeFile() {
    // Hämta sökvägen till den aktuella filen
    $currentFile = $_SERVER['SCRIPT_FILENAME'] ?? __FILE__;
    $currentDir = dirname($currentFile);
    $currentFileName = basename($currentFile, '.php');

    // Bestäm vilken readme-fil vi ska leta efter
    // Specialfall: impex_map.php använder impex_map_help.php
    $readmeFileName = 'readme.php';
    if ($currentFileName === 'impex_map') {
        $readmeFileName = 'impex_map_help.php';
    }

    // Kolla om readme-filen finns i samma mapp
    $readmePath = $currentDir . DIRECTORY_SEPARATOR . $readmeFileName;

    if (file_exists($readmePath)) {
        // Bygg web-sökväg baserat på PHP_SELF
        $scriptPath = $_SERVER['PHP_SELF'] ?? '';

        // Ta bort query string om den finns
        $scriptPath = strtok($scriptPath, '?');

        // Hämta katalog-sökvägen (ta bort filnamnet)
        $scriptDir = dirname($scriptPath);

        // Normalisera sökvägen
        $scriptDir = str_replace(['\\'], ['/'], $scriptDir);
        $scriptDir = rtrim($scriptDir, '/');

        // Bygg sökväg till readme-filen
        $readmeWebPath = $scriptDir . '/' . $readmeFileName;

        // Fixa dubbelslashes (men behåll första /)
        $readmeWebPath = preg_replace('#(?<!:)/(?=/)#', '', $readmeWebPath);

        return $readmeWebPath;
    }

    return null;
}
