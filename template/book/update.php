<html>
<body>
<a href="/">Список</a>
<h1>Изменение книги</h1>
<form action="?/book/update/id/<?= $book['id']?>/" method="post">
	<p>Название: <input type="text" name="name" value="<?= $book['name']?>"></p>
	<p>Автор: <input type="text" name="author" value="<?= $book['author']?>"></p>
	<p>Год: <input type="text" name="year" value="<?= $book['year']?>"></p>
	<p>Жанр: <input type="text" name="genre" value="<?= $book['genre']?>"></p>
	<p><input type="submit" value="Сохранить"></p>
</form>

</body>
</html>