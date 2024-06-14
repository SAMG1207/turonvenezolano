<?php

class Basic{

    public static function testString($data){
    
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        return $data;
    
}

public static function isPotentiallyDangerous(string $input_string):bool {
    // Detecting SQL Injection patterns
    $sql_patterns = ['/\' OR 1=1/', '/--/', '/;/', '/\/\*/'];
    foreach ($sql_patterns as $pattern) {
        if (preg_match($pattern, $input_string)) {
            return true;
        }
    }

    // Detecting XSS patterns
    if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $input_string)) {
        return true;
    }

    // Sanitizing using built-in functions
    $sanitized_string = htmlspecialchars($input_string, ENT_QUOTES, 'UTF-8');
    if ($input_string !== $sanitized_string) {
        return true;
    }

    return false;
}

}




