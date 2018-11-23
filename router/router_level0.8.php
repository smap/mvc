<?php

/*
site.ru/index.php?c=book&a=list
*/


// НАСТРОЙКА

function db()
{
    static $db = null;

    if ($db === null) {
        $config = [
            'host' => 'localhost',
            'dbname' => 'dbname1',
            'user' => 'user1',
            'pass' => 'pass1',
        ];
        try {
            $db = new PDO(
                'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=utf8',
                $config['user'],
                $config['pass']
            );
        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage() . '<br/>');
        }
    }
    return $db;
}

// Представление
function render($template)
{
    include 'template'.$template;
}

// КОНТРОЛЛЕР

if (! isset($_GET['c']) || ! isset($_GET['a'])) {
    $controller = 'book';
    $action = 'list';
} else {
    $controller = $_GET['c'];
    $action = $_GET['a'];
}

if ($controller == 'book') {
    if ($action == 'list') {
        controllerBookList();
    } elseif ($action == 'add') {
        controllerBookAdd();
    } elseif ($action == 'update') {
         controllerBookUpdate();
    } elseif ($action == 'delete') {
        controllerBookDelete();
    }
}




// КОНТРОЛЛЕР /controller/controllerBook.php:
function controllerBookList()
{
    bookList();
    render('/book/list.php');
}

function controllerBookAdd()
{
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

            $isAdd = bookAdd([
                'name' => $_POST['name'],
                'author' => $_POST['author'],
                'year' => $_POST['year'],
                'genre' => $_POST['genre'],
            ]);

            if ($isAdd) {
                header('Location: index.php');
            }
        }
    }
    render('/book/add.php');
}

function controllerBookUpdate()
{
    if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
        render('/error/404.php');
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
                $isUpdate = bookUpdate($_GET['id'], [
                    'name' => $_POST['name'],
                    'author' => $_POST['author'],
                    'year' => $_POST['year'],
                    'genre' => $_POST['genre'],
                ]);

                if ($isUpdate) {
                    header('Location: index.php?c=book&a=update&id=' . $_GET['id']);
                }
            }
        }
        render('/book/update.php');
    }
}

function controllerBookDelete()
{
    if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
        render('/error/404.php');
    } else {
        if (bookDelete($_GET['id'])) {
            header('Location: index.php');
        } else {
            render('/book/list.php');
        }
    }
}
// МОДЕЛЬ /model/Book.php:

function bookList()
{
    $result = null;
    $sth = db()->prepare('SELECT id, name, author, year, genre FROM book');
    if ($sth->execute()) {
        $result = $sth->fetchAll();
    }
    return $result;
}

function bookAdd($params)
{
    $sth = db()->prepare('INSERT INTO book(name, author, year, genre) VALUES(:name, :author, :year, :genre)');
    $sth->bindValue(':name', $params['name'], PDO::PARAM_STR);
    $sth->bindValue(':author', $params['author'], PDO::PARAM_STR);
    $sth->bindValue(':year', $params['year'], PDO::PARAM_INT);
    $sth->bindValue(':genre', $params['genre'], PDO::PARAM_STR);
    return $sth->execute();
}

function bookDelete($id)
{
    $sth = db()->prepare('DELETE FROM book WHERE id=:id');
    $sth->bindValue(':id', $id, PDO::PARAM_INT);
    return $sth->execute();
}


function bookUpdate($id, $params)
{
    $sth = db()->prepare('UPDATE book SET name=:name, author=:author, year=:year, genre=:genre  WHERE id=:id');

    $sth->bindValue(':name', $params['name'], PDO::PARAM_STR);
    $sth->bindValue(':author', $params['author'], PDO::PARAM_STR);
    $sth->bindValue(':year', $params['year'], PDO::PARAM_INT);
    $sth->bindValue(':genre', $params['genre'], PDO::PARAM_STR);
    $sth->bindValue(':id', $id, PDO::PARAM_INT);

    return $sth->execute();
}