<?php

class AppHelper {

    /**
     * 🗓️ แปลงวันที่เป็นรูปแบบไทย
     * ตัวอย่าง: 2025-06-29 -> 29 มิถุนายน 2568 เวลา 6.00 น.
     */
    public static function formatThaiDate($date) {
        if (!$date) return '-';
        
        $thaiMonths = [
            "", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
            "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ];
        
        $timestamp = strtotime($date);
        $day = date('j', $timestamp);
        $month = $thaiMonths[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp) + 543;
        
        return "$day $month $year เวลา 6.00 น.";
    }

    /**
     * 🔢 จัดรูปแบบทศนิยม (อย่างน้อย 2 ตำแหน่ง)
     * ตัดเลข 0 ต่อท้ายทิ้งถ้าเกินจำเป็น
     */
    public static function formatFloatAtLeastTwoDecimals($value, $minDecimals = 2) {
        $value = (float) $value;
        $raw = number_format($value, 10, '.', '');
        $trimmed = rtrim(rtrim($raw, '0'), '.');
        $parts = explode('.', $trimmed);
        
        if (count($parts) === 1 || strlen($parts[1]) < $minDecimals) {
            return number_format($value, $minDecimals, '.', '');
        } else {
            return $trimmed;
        }
    }

    /**
     * 🔢 จัดรูปแบบทศนิยม (อย่างน้อย 1 ตำแหน่ง)
     */
    public static function formatFloatAtLeastOneDecimals($value, $minDecimals = 1) {
        return self::formatFloatAtLeastTwoDecimals($value, $minDecimals);
    }

    /**
     * 🔢 จัดรูปแบบทศนิยม 1 หรือ 2 ตำแหน่ง (ตามที่มีใน logic เดิม)
     */
    public static function formatDecimal1or2($num) {
        $formatted = rtrim(rtrim(number_format($num, 2, '.', ''), '0'), '.');
        return (strpos($formatted, '.') === false) ? $formatted . '.0' : $formatted;
    }

    /**
     * 🌧️ หาคลาส CSS สีตามปริมาณฝน
     */
    public static function getRainfallClass($value) {
        if ($value >= 0.1 && $value < 0.5) return 'rainfall-SkyBlue';
        elseif ($value >= 0.5 && $value < 1) return 'rainfall-CornflowerBlue';
        elseif ($value >= 1 && $value < 2) return 'rainfall-SteelBlue';
        elseif ($value >= 2 && $value < 3) return 'rainfall-DodgerBlue';
        elseif ($value >= 3 && $value < 5) return 'rainfall-BlueRibbon';
        elseif ($value >= 5 && $value < 7) return 'rainfall-DarkBlue';
        elseif ($value >= 7 && $value < 10) return 'rainfall-PineGreen';
        elseif ($value >= 10 && $value < 15) return 'rainfall-LimeGreen';
        elseif ($value >= 15 && $value < 20) return 'rainfall-Green';
        elseif ($value >= 20 && $value < 25) return 'rainfall-LemonYellow';
        elseif ($value >= 25 && $value < 30) return 'rainfall-OrangeYellow';
        elseif ($value >= 30 && $value < 35) return 'rainfall-BurntOrange';
        elseif ($value >= 35 && $value < 40) return 'rainfall-Tangerine';
        elseif ($value >= 40 && $value < 45) return 'rainfall-Tan';
        elseif ($value >= 45 && $value < 50) return 'rainfall-RedOchre';
        elseif ($value >= 50 && $value < 60) return 'rainfall-Scarlet';
        elseif ($value >= 60 && $value < 70) return 'rainfall-DarkRed';
        elseif ($value >= 70 && $value < 80) return 'rainfall-Maroon';
        elseif ($value >= 80 && $value < 90) return 'rainfall-TyrianPurple';
        elseif ($value >= 90 && $value < 100) return 'rainfall-RoyalPurple';
        elseif ($value >= 100 && $value < 125) return 'rainfall-Amethyst';
        elseif ($value >= 125 && $value < 150) return 'rainfall-LavenderPurple';
        elseif ($value >= 150 && $value < 200) return 'rainfall-LightLavender';
        elseif ($value >= 200 && $value <= 300) return 'rainfall-Silver';
        else return 'rainfall-low';
    }

    /**
     * 💧 หาสีของน้ำตามเปอร์เซ็นต์
     */
    public static function getWaterColor($percent) {
        if ($percent <= 10) return 'brown';
        elseif ($percent <= 30) return 'yellow';
        elseif ($percent <= 70) return '#16d92a';
        elseif ($percent <= 100) return '#28c4ea';
        else return 'red';
    }

    /**
     * 📍 ดึงชื่ออำเภอจากข้อความที่ตั้ง (Regex)
     * เช่น "ต.บ้านกิ่ว อ.แม่ทะ" -> "แม่ทะ"
     */
    public static function extractAmphoe($location) {
        if (preg_match('/อ\.([^\s]+)/u', $location, $matches)) {
            return trim($matches[1]);
        }
        return 'ไม่ทราบอำเภอ';
    }
}
?>