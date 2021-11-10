<html>
 <head>
 
 </head>
 <body>

<?php
 
// Пути загрузки файлов
$path = 'Z:/home/localhost/www'; 
$tmp_path = 'Z:/home/localhost/www/mage'; //путь к временным файлам
// Массив допустимых значений типа файла
$types = array('image/gif', 'image/png', 'image/jpeg');
// Максимальный размер файла
$size = 10240000;
 
// Обработка запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
 // Проверяем тип файла
 if (!in_array($_FILES['picture']['type'], $types))
 die('<p>Запрещённый тип файла. <a href="?">Попробовать другой файл?</a></p>');
 
 // Проверяем размер файла
 if ($_FILES['picture']['size'] > $size)
 die('<p>Слишком большой размер файла. <a href="?">Попробовать другой файл?</a></p>');
 
 // Функция изменения размера
 // Изменяет размер изображения в зависимости от type:
 // type = 1 - баннер
 
 function resize($file, $type = 1)
 {
 global $tmp_path;
 
 // Ограничение по ширине в пикселях
 $max_thumb_size = 200;
 $max_size = 100;
  
 // Cоздаём исходное изображение на основе исходного файла
 if ($file['type'] == 'image/jpeg')
 $source = imagecreatefromjpeg($file['tmp_name']);
 elseif ($file['type'] == 'image/png')
 $source = imagecreatefrompng($file['tmp_name']);
 elseif ($file['type'] == 'image/gif')
 $source = imagecreatefromgif($file['tmp_name']);
 else
 return false;

 
 // В зависимости от типа  устанавливаем ограничение по ширине.
 if ($type == 1)
 $w = $max_thumb_size;
  
 // Если ширина больше заданной
 elseif ($w_src > $w)
 {
 // Вычисление пропорций
 $ratio = $w_src/$w;
 $w_dest = round($w_src/$ratio);
 $h_dest = round($h_src/$ratio);
 
 // Создаём пустую картинку
 $dest = imagecreatetruecolor($w_dest, $h_dest);
 
 // Копируем старое изображение в новое с изменением параметров
 imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
 
 // Вывод картинки и очистка памяти
 imagejpeg($dest, $tmp_path . $file['name']);
 imagedestroy($dest);
 imagedestroy($src);
 
 return $file['name'];
 }
 else
 {
 // Вывод картинки и очистка памяти
 imagejpeg($src, $tmp_path . $file['name']);
 imagedestroy($src);
 
 return $file['name'];
 }
 }
 
 $name = resize($_FILES['picture'], $_POST['file_type'], $_POST['file_rotate']);
 
 // Загрузка файла и вывод сообщения
 if (!@copy($tmp_path . $name, $path . $name))
 echo '<p>Что-то пошло не так!</p>';
 else
 echo '<p>Загрузка прошла удачно <img src="' . $path . $_FILES['picture']['name'] . '">Посмотреть</a>.</p>';
 
 // Удаляем временный файл
 //unlink($tmp_path . $name);
}
 
?>
 <form method="post" enctype="multipart/form-data">
 <input type="file" name="picture">
 <br>
 <label>Тип загрузки</label>
 <br>
 <select name="file_type">
 <option value="1">Баннер</option>
 <option value="2">Большое изображение</option>
 </select>
 <br>
 <input type="submit" value="Загрузить">
 </form>
 </body>
</html>
