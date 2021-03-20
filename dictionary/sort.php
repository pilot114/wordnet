<?php

function sortFile($filename) {
    $strings = explode("\n", file_get_contents($filename));
    natsort($strings);
    file_put_contents($filename, implode("\n", $strings));
}

sortFile('./difficult.txt');
sortFile('./easy.txt');
