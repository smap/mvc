<?php

/**
 * Второй уровень понимания роутеров.
 * Сделаем более универсальным
 * Пример урла: /?c={controller}&a={action}&{param1}={value1}&{param2}={value2}
 * /?c=book&a=update&id=1
 */

if (! isset($_GET['c']) || ! isset($_GET['a'])) {
    $controller = 'book';
    $action = 'list';
} else {
    $controller = $_GET['c'];
    $action = $_GET['a'];
}



$controllerText = $controller . 'Controller';
$controllerFile = 'controller/' . ucfirst($controllerText) . '.php';
if (is_file($controllerFile)) {
    include $controllerFile;
    if (class_exists($controllerText)) {
        $controller = new $controllerText();
        if (method_exists($controller, $action)) {
            $controller->$action();
        }
    }
}
