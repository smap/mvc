<?php

/*
site.ru/index.php = site.ru/index.php?c=book&a=list
site.ru/index.php?c=book&a=list - список
site.ru/index.php?c=book&a=add - добавление
site.ru/index.php?c=book&a=delete&id=[id] - удаление
site.ru/index.php?c=book&a=update&id=[id] - изменение
*/

$config = [
    'host' => 'localhost',
    'dbname' => 'dbname1',
    'user' => 'user1',
    'pass' => 'pass1',
];
try {
    $db = new PDO(
        'mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8',
        $config['user'],
        $config['pass']
    );
} catch (PDOException $e) {
    die('Database error: '.$e->getMessage().'<br/>');
}



if (! isset($_GET['c']) || ! isset($_GET['a'])) {
    $controller = 'book';
    $action = 'list';
} else {
    $controller = $_GET['c'];
    $action = $_GET['a'];
}

if ($controller == 'book') {
    if ($action == 'list') {

        $sth = $db->prepare('SELECT id, name, author, year, genre FROM book');
        $bookList = [];
        if ($sth->execute()) {
            $bookList = $sth->fetchAll();
        }
        include 'template/book/list.php';
    } elseif ($action == 'add') {
        if (count($_POST) > 0) {
            $errors = [];
            if (empty($_POST['name'])) {
                $errors['name'] = 'Добавьте название';
            }
            if (empty($_POST['author'])) {
                $errors['author'] = 'Добавьте автора';
            }
            if (empty($_POST['year'])) {
                $errors['year'] = 'Добавьте год';
            }
            if (empty($_POST['genre'])) {
                $errors['genre'] = 'Добавьте жанр';
            }

            if (count($errors) == 0) {

                $sth = $db->prepare('UPDATE book SET name=:name, author=:author, year=:year, genre=:genre  WHERE id=:id');

                $sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
                $sth->bindValue(':author', $_POST['author'], PDO::PARAM_STR);
                $sth->bindValue(':year', $_POST['year'], PDO::PARAM_INT);
                $sth->bindValue(':genre', $_POST['genre'], PDO::PARAM_STR);

                if ($sth->execute()) {
                    header('Location: index.php?c=book&a=list');
                } else {
                    $errors['global'] = 'Ошибка сохранения';
                }
            }
        }
        include 'template/book/add.php';

    } elseif ($action == 'update') {
        if (! isset($_GET['id']) && ! is_numeric($_GET['id'])) {
            include 'template/error/404.php';
        } else {
            if (count($_POST) > 0) {
                $errors = [];
                if (empty($_POST['name'])) {
                    $errors['name'] = 'Добавьте название';
                }
                if (empty($_POST['author'])) {
                    $errors['author'] = 'Добавьте автора';
                }
                if (empty($_POST['year'])) {
                    $errors['year'] = 'Добавьте год';
                }
                if (empty($_POST['genre'])) {
                    $errors['genre'] = 'Добавьте жанр';
                }

                if (count($errors) == 0) {

                    $sth = $db->prepare('INSERT INTO book(name, author, year, genre) VALUES(:name, :author, :year, :genre)');
                    $sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
                    $sth->bindValue(':author', $_POST['author'], PDO::PARAM_STR);
                    $sth->bindValue(':year', $_POST['year'], PDO::PARAM_INT);
                    $sth->bindValue(':genre', $_POST['genre'], PDO::PARAM_STR);
                    $sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

                    if ($sth->execute()) {
                        header('Location: index.php?c=book&a=update&id=' . $_GET['id']);
                    } else {
                        $errors['global'] = 'Ошибка сохранения';
                    }
                }
            }

            render('/book/update.php');
        }
    } elseif ($action == 'delete') {
        if (! isset($_GET['id']) && ! is_numeric($_GET['id'])) {
            include 'template/error/404.php';
        } else {
            $sth = $db->prepare('DELETE FROM book WHERE id=:id');
            $sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
            if ($sth->execute()) {
                header('Location: index.php?c=book&a=list');
            } else {
                $errors['global'] = 'Ошибка удаления';
                include 'template/book/list.php';
           }
        }
    }
}