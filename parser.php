<?php

//include 'vendor/autoload.php';

//$parser = new \Smalot\PdfParser\Parser();
//$pdf = $parser->parseFile('./dictionary/ozhegov.pdf');

//$details = $pdf->getDetails();
//foreach ($details as $property => $value) {
//    if (is_array($value)) {
//        $value = implode(', ', $value);
//    }
//    echo $property . ' => ' . $value . "\n";
//}

//$pages = $pdf->getPages();
//$text = $pages[4]->getText();
//$text = $pages[4]->getTextArray();

function removeTranslit($word) {
    if (!mb_strlen(mb_ereg_replace("[Ѐ-Я\-#]", "", $word)) > 1) {
        return $word;
    }
    $letters = preg_split("//u", $word, -1, PREG_SPLIT_NO_EMPTY);
    $fixed = [];
    foreach ($letters as $letter) {
        $n = mb_ord($letter);
        $map = [
            79 => 'О',
            77 => 'М',
            65 => 'А',
            88 => 'Х',
            80 => 'Р',
            72 => 'Н',
            75 => 'К',
            66 => 'В',
            67 => 'С',
            84 => 'Т',
            69 => 'Е',
        ];

        if (($n < 1024 || $n > 1071) && isset($map[$n])) {
            $fixed[] = $map[$n];
        } else {
            $fixed[] = $letter;
        }
    }
    return implode('', $fixed);
}

/**
 * Формат словаря:
 * - каждое определяемое слово в начале строки в верхнем регистре, отделено пробелом
 * - определяемые слова состоят из русских букв и дефисов
 * - слова приставки оканчиваются на #
 */

$lines = explode("\n", file_get_contents('./dictionary/ozhegov.txt'));

$res = [];
foreach ($lines as $i => $line) {
    $firstLetter = mb_substr($line, 0, 1);
    if (!preg_match('~^\p{Lu}~u', $firstLetter)) {
        var_dump($line);
        var_dump($firstLetter);
        die();
    }

    $words = explode(' ', trim($line));
    foreach ($words as $j => $word) {
        if ($j === 0) continue;
        if (mb_strlen(mb_ereg_replace("[Ѐ-Я\-#]", "", $word)) === 0) {
            if (mb_strlen($word) > 1) {
//                var_dump($word);
            }
        }
    }

    $firstWord = preg_replace('/\.\.\./', '#', $words[0]);
    $words[0] = preg_replace('/[0-9,.:;\'\"!]+/', '', $firstWord);

    $res[] = implode(' ', $words);
}
file_put_contents('./dictionary/ozhegov.txt', implode("\n", $res));