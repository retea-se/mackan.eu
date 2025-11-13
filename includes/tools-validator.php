<?php
/**
 * tools-validator.php - Gemensam valideringsfunktion för verktyg
 * 
 * Denna fil innehåller valideringsfunktioner för att säkra input från formulär
 * och API-anrop. Använd dessa funktioner istället för att direkt använda $_POST
 * utan validering.
 */

/**
 * Validerar numeriskt värde
 * 
 * @param mixed $value Värdet att validera
 * @param array $options Valideringsalternativ ['min' => int, 'max' => int, 'default' => mixed]
 * @return float|int Validerat numeriskt värde
 */
function validateNumeric($value, $options = []) {
    $min = $options['min'] ?? 0;
    $max = $options['max'] ?? PHP_FLOAT_MAX;
    $default = $options['default'] ?? 0;
    
    if (!isset($value) || $value === '') {
        return $default;
    }
    
    if (!is_numeric($value)) {
        return $default;
    }
    
    $numValue = (float)$value;
    
    if ($numValue < $min) {
        return $min;
    }
    
    if ($numValue > $max) {
        return $max;
    }
    
    return $numValue;
}

/**
 * Validerar string-värde
 * 
 * @param mixed $value Värdet att validera
 * @param array $options Valideringsalternativ ['min' => int, 'max' => int, 'default' => string, 'trim' => bool]
 * @return string Validerat string-värde
 */
function validateString($value, $options = []) {
    $min = $options['min'] ?? 0;
    $max = $options['max'] ?? 1000;
    $default = $options['default'] ?? '';
    $trim = $options['trim'] ?? true;
    
    if (!isset($value)) {
        return $default;
    }
    
    $stringValue = (string)$value;
    
    if ($trim) {
        $stringValue = trim($stringValue);
    }
    
    $length = strlen($stringValue);
    
    if ($length < $min) {
        return $default;
    }
    
    if ($length > $max) {
        return substr($stringValue, 0, $max);
    }
    
    return $stringValue;
}

/**
 * Validerar enum-värde (whitelist)
 * 
 * @param mixed $value Värdet att validera
 * @param array $allowed Tillåtna värden
 * @param mixed $default Standardvärde om valideringen misslyckas
 * @return mixed Validerat värde
 */
function validateEnum($value, $allowed = [], $default = null) {
    if (!isset($value)) {
        return $default;
    }
    
    if (!in_array($value, $allowed, true)) {
        return $default;
    }
    
    return $value;
}

/**
 * Validerar JSON-sträng
 * 
 * @param string $value JSON-sträng att validera
 * @param mixed $default Standardvärde om valideringen misslyckas
 * @return array|null Validerat array eller null
 */
function validateJson($value, $default = null) {
    if (empty($value)) {
        return $default;
    }
    
    $decoded = json_decode($value, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $default;
    }
    
    if (!is_array($decoded)) {
        return $default;
    }
    
    return $decoded;
}

/**
 * Validerar URL
 * 
 * @param string $value URL att validera
 * @param mixed $default Standardvärde om valideringen misslyckas
 * @return string|null Validerat URL eller null
 */
function validateUrl($value, $default = null) {
    if (empty($value)) {
        return $default;
    }
    
    $url = filter_var(trim($value), FILTER_VALIDATE_URL);
    
    if ($url === false) {
        return $default;
    }
    
    return $url;
}

/**
 * Validerar email
 * 
 * @param string $value Email att validera
 * @param mixed $default Standardvärde om valideringen misslyckas
 * @return string|null Validerat email eller null
 */
function validateEmail($value, $default = null) {
    if (empty($value)) {
        return $default;
    }
    
    $email = filter_var(trim($value), FILTER_VALIDATE_EMAIL);
    
    if ($email === false) {
        return $default;
    }
    
    return $email;
}

/**
 * Saniterar output för HTML
 * 
 * @param mixed $value Värdet att sanitera
 * @return string Saniterat värde
 */
function sanitizeOutput($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

