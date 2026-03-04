<?php
function numberToWord($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else
            $str[] = null;
    }
    $Taka = implode('', array_reverse($str));
    $poysa = ($decimal) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Poysa' : '';
    return ($Taka ? $Taka . 'Taka ' : '') . $poysa . 'Only.';
}

// --------------------------formatCurrency ------------------

if (!function_exists('formatCurrency')) {
    function formatCurrency($number)
    {
        $number = floatval($number);

        // Ensure the number is formatted with two decimal places
        $number_parts = explode('.', number_format($number, 2, '.', ''));
        $integer_part = $number_parts[0];
        $decimal_part = isset($number_parts[1]) ? $number_parts[1] : '00';

        // Reverse the integer part for easier manipulation
        $reversed_integer_part = strrev($integer_part);
        $formatted_integer_part = '';

        // Process the reversed integer part in chunks of 3 and then 2
        for ($i = 0; $i < strlen($reversed_integer_part); $i += ($i < 3) ? 3 : 2) {
            if ($i > 0) {
                $formatted_integer_part .= ',';
            }
            $formatted_integer_part .= substr($reversed_integer_part, $i, ($i < 3) ? 3 : 2);
        }

        // Reverse the formatted integer part back to normal
        $formatted_integer_part = strrev($formatted_integer_part);

        return $formatted_integer_part . '.' . $decimal_part;
    }
}

if (!function_exists('bnNumber')) {
    function bnNumber($value): string
    {
        $en = ['0','1','2','3','4','5','6','7','8','9','.',',','-'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯','.',',','-'];
        return str_replace($en, $bn, (string)$value);
    }
}

if (!function_exists('bnDate')) {
    function bnDate($date, $format = 'd-m-Y'): string
    {
        try {
            $formatted = \Carbon\Carbon::parse($date)->format($format);
        } catch (\Throwable $e) {
            $formatted = date($format);
        }
        return bnNumber($formatted);
    }
}

// if (!function_exists('formatCurrency')) {
//     function formatCurrency($number)
//     {
//         $number = floatval($number);

//         // Ensure the number is formatted with two decimal places
//         $number_parts = explode('.', number_format($number, 2, '.', ''));
//         $integer_part = $number_parts[0];
//         $decimal_part = isset($number_parts[1]) ? $number_parts[1] : '00';

//         // Handle negative numbers
//         $is_negative = $number < 0;
//         if ($is_negative) {
//             $integer_part = ltrim($integer_part, '-');
//         }

//         // Reverse the integer part for easier manipulation
//         $reversed_integer_part = strrev($integer_part);
//         $formatted_integer_part = '';

//         // Process the reversed integer part in chunks of 3 and then 2
//         for ($i = 0; $i < strlen($reversed_integer_part); $i += ($i < 3) ? 3 : 2) {
//             if ($i > 0) {
//                 $formatted_integer_part .= ',';
//             }
//             $formatted_integer_part .= substr($reversed_integer_part, $i, ($i < 3) ? 3 : 2);
//         }

//         // Reverse the formatted integer part back to normal
//         $formatted_integer_part = strrev($formatted_integer_part);

//         // Add the negative sign back if necessary
//         if ($is_negative) {
//             $formatted_integer_part = '-' . $formatted_integer_part;
//         }

//         return $formatted_integer_part . '.' . $decimal_part;
//     }
// }





