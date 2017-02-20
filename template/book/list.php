<html>
<body>
<p><a href="?/book/add/">Добавить книгу</a></p>
<table border="1">
	<?php foreach ($books as $book) : ?>
		<tr>
			<td><?= $book['name']?></td>
			<td><?= $book['author']?></td>
			<td><?= $book['year']?></td>
			<td><?= $book['genre']?></td>
			<td><a href="?/book/delete/id/<?= $book['id']?>/">Удалить</a></td>
			<td><a href="?/book/update/id/<?= $book['id']?>/">Изменить</a></td>
		</tr>
	<?php endforeach ?>
</table>

</body>
</html>