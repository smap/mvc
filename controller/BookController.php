<?php

class BookController
{
	private $model = null;

	function __construct($db)
	{
		include 'model/Book.php';
		$this->model = new Book($db);
	}

	/**
	 * Отображаем шаблон
	 * @param $template
	 * @param $params
	 */
	private function render($template, $params = [])
	{
		$fileTemplate = 'template/'.$template;
		if (is_file($fileTemplate)) {
			ob_start();
			if (count($params) > 0) {
				extract($params);
			}
			include $fileTemplate;
			return ob_get_clean();
		}
	}

	/**
	 * Форма добавление книги
	 * @param $params array
	 * @return mixed
	 */
	function getAdd()
	{
		echo $this->render('book/add.php');
	}

	/**
	 * Добавление книги
	 * @param $params array
	 * @return mixed
	 */
	function postAdd($params, $post)
	{
		$updateParam = [];
		if (isset($post['name']) && isset($post['author']) && isset($post['year']) && isset($post['genre'])) {
			$idAdd = $this->model->add([
				'name' => $post['name'],
				'author' => $post['author'],
				'year' => $post['year'],
				'genre' => $post['genre'],
			]);
			if ($idAdd) {
				header('Location: /');
			}
		}
	}

	/**
	 * Удаление книги
	 * @param $id
	 */
	public function getDelete($params)
	{
		if (isset($params['id']) && is_numeric($params['id'])) {
			$isDelete = $this->model->delete($params['id']);
			if ($isDelete) {
				header('Location: /');
			}
		}
	}

	/**
	 * Форма редактирование данных
	 * @param $id
	 */

	public function getUpdate($params)
	{
		if (isset($params['id']) && is_numeric($params['id'])) {
			$book = $this->model->find($params['id']);
			echo $this->render('book/update.php', ['book' => $book]);
		}
	}


	/**
	 * Изменение данных о книге
	 * @param $id
	 */

	public function postUpdate($params, $post)
	{
		if (isset($params['id']) && is_numeric($params['id'])) {
			$updateParam = [];
			if (isset($post['name'])) {
				$updateParam['name'] = $post['name'];
			}
			if (isset($post['author'])) {
				$updateParam['author'] = $post['author'];
			}
			if (isset($post['year']) && is_numeric($post['year'])) {
				$updateParam['year'] = $post['year'];
			}
			if (isset($post['genre'])) {
				$updateParam['genre'] = $post['genre'];
			}
			$isUpdate = $this->model->update($params['id'], $updateParam);

			if ($isUpdate) {
				header('Location: /');
			}
		}
	}

	/**
	 * Получение всех книг
	 * @return array
	 */
	public function getList()
	{
		$books = $this->model->findAll();
		echo $this->render('book/list.php', ['books' => $books]);
	}

}

