<?php
// файл и новый размер
$filename = '/var/www/dokuwiki'.$_GET['src'];
$percent = 0.1;

// echo "$filename";
// тип содержимого
header('Content-Type: image/jpeg');

// получение нового размера
list($width, $height) = getimagesize($filename);
$newwidth = $width * $percent;
$newheight = $height * $percent;

// загрузка
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($filename);

// изменение размера
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// вывод
imagejpeg($thumb);
?>