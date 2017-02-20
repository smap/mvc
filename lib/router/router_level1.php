<?php

/**
 * Первый уровень понимания роутеров.
 * Намерено сделано через if и else
 * Пример урла: /?/{controller}/{action}/{param1}/{value1}/{param2}/{value2}/
 * /?/book/update/id/1/
 */

$params = [];
$pathList = preg_split('/\//', $_SERVER['REQUEST_URI'], -1, PREG_SPLIT_NO_EMPTY);
array_shift($pathList);
// Значение по умолчанию
if (count($pathList) < 2) {
	$pathList = ['book', 'list'];
}
if (count($pathList) >= 2) {
	$controller = array_shift($pathList);
	$action = array_shift($pathList);
	foreach ($pathList as $i => $value) {
		if ($i % 2 == 0 && isset($pathList[$i + 1])) {
			$params[$pathList[$i]] = $pathList[$i + 1];
		}
	}
	if ($controller == 'book') {
		include 'controller/BookController.php';
		$books = new BookController($db);
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			if ($action == 'list') {
				$books->getList();
			} elseif ($action == 'add') {
				$books->getAdd();
			} elseif ($action == 'update') {
				$books->getUpdate($params);
			} elseif ($action == 'delete') {
				$books->getDelete($params);
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($action == 'add') {
				$books->postAdd($params, $_POST);
			} elseif ($action == 'update') {
				$books->postUpdate($params, $_POST);
			}
		}
	}
}
