<?php

class CoordinateConverter {
    // Definierade parametrar för SWEREF99 TM och RT90
    private static $sweref99_tm = [
        'central_meridian' => 15,
        'scale' => 0.9996,
        'false_northing' => 0,
        'false_easting' => 500000
    ];

    private static $rt90 = [
        'central_meridian' => 15.806,
        'scale' => 1.0,
        'false_northing' => -667.282,
        'false_easting' => 1500064.274
    ];

    /**
     * Identifiera koordinatsystem utifrån format.
     */
    public static function identifyFormat($input) {
        $input = trim($input);

        if (preg_match('/^-?\d{1,2}\.\d+,\s*-?\d{1,3}\.\d+$/', $input)) {
            return 'WGS84';
        }

        if (preg_match('/^\d{7},\s*\d{6}$/', $input)) {
            return 'SWEREF99TM';
        }

        if (preg_match('/^\d{7},\s*\d{7}$/', $input)) {
            return 'RT90';
        }

        return 'UNKNOWN';
    }

    /**
     * Konvertera från WGS84 till SWEREF99 TM.
     */
    public static function wgs84ToSweref99($lat, $lon) {
        return self::convertToGaussKruger($lat, $lon, self::$sweref99_tm);
    }

    /**
     * Konvertera från SWEREF99 TM till WGS84.
     */
    public static function sweref99ToWgs84($north, $east) {
        return self::convertFromGaussKruger($north, $east, self::$sweref99_tm);
    }

    /**
     * Konvertera från WGS84 till RT90.
     */
    public static function wgs84ToRt90($lat, $lon) {
        return self::convertToGaussKruger($lat, $lon, self::$rt90);
    }

    /**
     * Konvertera från RT90 till WGS84.
     */
    public static function rt90ToWgs84($north, $east) {
        return self::convertFromGaussKruger($north, $east, self::$rt90);
    }

    /**
     * Transversala Mercatorprojektionen (Gauss-Krüger)
     */
    private static function convertToGaussKruger($lat, $lon, $params) {
        $a = 6378137.0; // WGS84 semi-major axis
        $f = 1 / 298.257222101; // WGS84 flattening
        $e2 = 2 * $f - $f * $f;
        $n = $a / sqrt(1 - $e2 * pow(sin(deg2rad($lat)), 2));
        $t = tan(deg2rad($lat));
        $l = deg2rad($lon - $params['central_meridian']);
        $m = $a * (deg2rad($lat) - $e2 * sin(2 * deg2rad($lat)) / 2);

        $north = $params['scale'] * ($m + $n * $t * $l * $l / 2) + $params['false_northing'];
        $east = $params['scale'] * ($n * $l + $n * pow($l, 3) / 6) + $params['false_easting'];

        return [
            'north' => round($north, 3),
            'east' => round($east, 3)
        ];
    }

    /**
     * Invertera Gauss-Krüger till WGS84
     */
    private static function convertFromGaussKruger($north, $east, $params) {
        $a = 6378137.0;
        $f = 1 / 298.257222101;
        $e2 = 2 * $f - $f * $f;
        $m0 = ($north - $params['false_northing']) / $params['scale'];
        $mu = $m0 / ($a * (1 - $e2 / 4 - 3 * pow($e2, 2) / 64 - 5 * pow($e2, 3) / 256));

        $lat = rad2deg($mu + (3 * $e2 / 2 - 27 * pow($e2, 3) / 32) * sin(2 * $mu)
                + (21 * $e2 * $e2 / 16 - 55 * pow($e2, 4) / 32) * sin(4 * $mu));

        $lon = $params['central_meridian'] + rad2deg(($east - $params['false_easting']) / ($a * cos(deg2rad($lat))));

        return [
            'lat' => round($lat, 6),
            'lon' => round($lon, 6)
        ];
    }

    /**
     * Huvudfunktion för att konvertera mellan olika system.
     */
    public static function convert($input, $targetSystem) {
        $format = self::identifyFormat($input);
        
        if ($format === 'UNKNOWN') {
            return ['error' => 'Okänt format'];
        }

        $coords = explode(',', $input);
        $lat = floatval(trim($coords[0]));
        $lon = floatval(trim($coords[1]));

        switch ($format) {
            case 'WGS84':
                if ($targetSystem === 'SWEREF99TM') return self::wgs84ToSweref99($lat, $lon);
                if ($targetSystem === 'RT90') return self::wgs84ToRt90($lat, $lon);
                break;

            case 'SWEREF99TM':
                if ($targetSystem === 'WGS84') return self::sweref99ToWgs84($lat, $lon);
                break;

            case 'RT90':
                if ($targetSystem === 'WGS84') return self::rt90ToWgs84($lat, $lon);
                break;
        }

        return ['error' => 'Omvandling ej stödd'];
    }
}
