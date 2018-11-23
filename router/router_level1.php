<?php

/**
 * Первый уровень понимания роутеров.
 * Намерено сделано через if и else
 * Пример урла: /?c={controller}&a={action}&{param1}={value1}&{param2}={value2}
 * /?c=book&a=update&id=1
  /?c=book&a=add
 */

if (! isset($_GET['c']) || ! isset($_GET['a'])) {
    $controller = 'book';
    $action = 'list';
} else {
    $controller = $_GET['c'];
    $action = $_GET['a'];
}

if ($controller == 'book') {
    include 'controller/BookController.php';
    $bookController = new BookController();
    if ($action == 'list') {
        $bookController->getList();
        //controllerBookList();
    } elseif ($action == 'add') {
        $bookController->add();
        //controllerBookAdd();
    } elseif ($action == 'update') {
        $bookController->update();
        //controllerBookUpdate();
    } elseif ($action == 'delete') {
         $bookController->delete();
         //controllerBookDelete();
    }
}
