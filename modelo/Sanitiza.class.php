<?php
class Sanitiza {

    // Sanitiza una cadena para evitar inyecciones SQL
    public static function SQL($string, $min = '', $max = '') {
        $string = trim($string);
        $len = strlen($string);
        if (($min !== '' && $len < $min) || ($max !== '' && $len > $max)) {
            return false;
        }

        $pattern = [
            '/(\\\\)/', // barra invertida
            '/"/',      // comillas dobles
            "/'/"       // comillas simples
        ];
        $replacement = [
            '\\\\\\',
            '\"',
            "\\'"
        ];

        return preg_replace($pattern, $replacement, $string);
    }

    // Valida DNI argentino (7 u 8 dígitos, sin puntos)
    public static function DNI($string) {
        $string = trim($string);
        return preg_match('/^\d{7,8}$/', $string) ? $string : false;
    }

    // Valida apellido (solo letras y espacios, permite acentos y Ñ/ñ)
    public static function APELLIDO($string) {
        $string = trim($string);
        return preg_match('/^[\p{L}\s]+$/u', $string) ? ucfirst(strtolower($string)) : false;
    }

    // Valida nombre (igual que apellido)
    public static function NOMBRE($string) {
        $string = trim($string);
        return preg_match('/^[\p{L}\s]+$/u', $string) ? ucfirst(strtolower($string)) : false;
    }

    // Valida email
    public static function EMAIL($string) {
        $string = trim($string);
        return filter_var($string, FILTER_VALIDATE_EMAIL) ? $string : false;
    }

    // Valida teléfono completo (ej: 3411234567 o 1134567890)
    public static function TELEFONO($string) {
        $string = preg_replace('/\D/', '', $string); // Elimina no dígitos
        return preg_match('/^\d{8,12}$/', $string) ? $string : false;
    }

    // Valida materias aprobadas (debe ser entero entre 0 y 50)
    public static function MATERIAS_APROBADAS($string) {
        $string = trim($string); // Quita espacios extremos
    
        // Validación sin límite, pero con formato estricto
        if (!preg_match('/^([\p{L}\d]+(?:[\s\p{L}\d]+)*)(,\s*[\p{L}\d]+(?:[\s\p{L}\d]+)*)*$/u', $string)) {
            return false;
        }
    
        return ucwords(mb_strtolower($string, 'UTF-8'));
    }
    

}
?>
