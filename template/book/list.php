<html>
<body>
<p><a href="?c=book&a=add/">Добавить книгу</a></p>
<?php foreach ($errors as $errorName => $errorValue) : ?>
    <p><?= $errorValue?></p>
<?php endforeach ?>

<table border="1">
	<?php foreach ($bookList as $book) : ?>
		<tr>
			<td><?= $book['name']?></td>
			<td><?= $book['author']?></td>
			<td><?= $book['year']?></td>
			<td><?= $book['genre']?></td>
			<td><a href="?c=book&=adelete&id=<?= $book['id']?>">Удалить</a></td>
			<td><a href="?c=book&a=update&id=<?= $book['id']?>">Изменить</a></td>
		</tr>
	<?php endforeach ?>
</table>

</body>
</html>