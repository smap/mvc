<?php

/**
 * Третий уровень понимания роутеров.
 * Уберём проверки файлов. Добавим настройки
 * Но помним, что многие вещи не реализованны
 * Пример урла: ?/book/update/
 */

class Router
{
	private $dirConroller = '';
	private $db = '';
	private $urls = [];

	function __construct($dirConroller, $db)
	{
		$this->dirConroller = $dirConroller;
		$this->db = $db;
	}

	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@getUpdate
	 */
	public function get($url, $controllerAndAction, $params = [])
	{
		$this->add('GET', $url, $controllerAndAction, $params);
	}

	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@postUpdate
	 */
	public function post($url, $controllerAndAction, $params = [])
	{
		$this->add('POST', $url, $controllerAndAction, $params);
	}

	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@list
	 */
	public function add($method, $url, $controllerAndAction, $params)
	{
		list($controller, $action) = explode('@', $controllerAndAction);

		$this->urls[$method][$url] = [
			'controller' => $controller,
			'action' => $action,
			'params' => $params
		];
	}

	/**
	 * Подключение контроллеров
	 * @param $url текущий урл
	 */
	public function run($currentUrl)
	{
		if (isset($this->urls[$_SERVER['REQUEST_METHOD']])) {
			foreach ($this->urls[$_SERVER['REQUEST_METHOD']] as $url => $urlData) {
				if (preg_match('(^'.$url.'$)', $currentUrl, $matchList)) {
					$params = [];
					foreach ($urlData['params'] as $param => $i) {
						$params[$param] = $matchList[$i];
					}
					include $this->dirConroller.$urlData['controller'].'.php';
					$controller = new $urlData['controller']($this->db);
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$controller->$urlData['action']($params, $_POST);
					} else {
						$controller->$urlData['action']($params);
					}
				}
			}
		}
	}
}

$router = new Router('controller/', $db);

$router->get('/', 'BookController@getList');
$router->get('/book/add/', 'BookController@getAdd');
$router->post('/book/add/', 'BookController@postAdd');
$router->get('/book/update/id/(\d+)/', 'BookController@getUpdate', ['id' => 1]);
$router->post('/book/update/id/(\d+)/', 'BookController@postUpdate', ['id' => 1]);
$router->get('/book/delete/id/(\d+)/', 'BookController@getDelete', ['id' => 1]);



/*
Удаляем "/?", потому что не сделали настройки на серверах
 */
$currentUrl = str_replace('/?', '', $_SERVER['REQUEST_URI']);

/*
Если добавить конфиг в
Apache
	Options +FollowSymLinks
	RewriteEngine On
	RewriteRule ^(.*)$ index.php [NC,L]

Nginx:
	location / {
		try_files $uri $uri/ /index.php?$query_string;
	}

то:
$currentUrl = $_SERVER['REQUEST_URI'];
*/
$router->run($currentUrl);

// Продолжение следует...