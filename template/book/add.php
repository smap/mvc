<html>
<body>
<a href="/">Список</a>
<h1>Добавление книги</h1>
<?php foreach ($errors as $errorName => $errorValue) : ?>
    <p><?= $errorValue?></p>
<?php endforeach ?>
<form action="?c=book&a=add" method="post">
	<p>Название: <input type="text" name="name" value=""></p>
	<p>Автор: <input type="text" name="author" value=""></p>
	<p>Год: <input type="text" name="year" value=""></p>
	<p>Жанр: <input type="text" name="genre" value=""></p>
	<p><input type="submit" value="Добавить"></p>
</form>

</body>
</html>