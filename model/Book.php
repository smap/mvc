<?php

class Book
{
	private $db = null;

	function __construct($db)
	{
		$this->db = $db;
	}

	/**
	* Добавление книги
	* @param $params array
	* @return mixed
	*/

	function add($params)
	{
		$sth = $this->db->prepare(
			'INSERT INTO book(name, author, year, genre)'
			.' VALUES(:name, :author, :year, :genre)'
		);

		$sth->bindValue(':name', $params['name'], PDO::PARAM_STR);
		$sth->bindValue(':author', $params['author'], PDO::PARAM_STR);
		$sth->bindValue(':year', $params['year'], PDO::PARAM_INT);
		$sth->bindValue(':genre', $params['genre'], PDO::PARAM_STR);

		return $sth->execute();
	}

	/**
	 * Удаление книги
	* @param $id int
	* @return mixed
	*/
	function delete($id)
	{
		$sth = $this->db->prepare('DELETE FROM `book` WHERE id=:id');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		return $sth->execute();
	}

	/**
	* @param $id int
	* @param $params array
	* @return mixed
	*/
	function update($id, $params)
	{
		if (count($params) == 0) {
			return false;
		}
		$update = [];
		foreach ($params as $param => $value) {
			$update[] = $param.'`=:'.$param;
		}
		$sth = $this->db->prepare('UPDATE `book` SET `'.implode(', `', $update).' WHERE `id`=:id');

		if (isset($params['name'])) {
			$sth->bindValue(':name', $params['name'], PDO::PARAM_INT);
		}
		if (isset($params['author'])) {
			$sth->bindValue(':author', $params['author'], PDO::PARAM_STR);
		}
		if (isset($params['year'])) {
			$sth->bindValue(':year', $params['year'], PDO::PARAM_INT);
		}
		if (isset($params['genre'])) {
			$sth->bindValue(':genre', $params['genre'], PDO::PARAM_STR);
		}
		$sth->bindValue(':id', $id, PDO::PARAM_INT);

		return $sth->execute();
	}

	/**
	* Получение всех книг
	* @return array
	*/
	public function findAll()
	{
		$sth = $this->db->prepare('SELECT `id`, `name`, `author`, `year`, `genre` FROM `book`');
		if ($sth->execute()) {
			return $sth->fetchAll();
		}
		return false;
	}

	/**
	 * Получение одной книги
	 * @param $id int
	 * @return array
	 */
	public function find($id)
	{
		$sth = $this->db->prepare('SELECT `id`, `name`, `author`, `year`, `genre` FROM `book` WHERE id=:id');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
}

