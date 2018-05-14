<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);



class Di
{
    static $di = null;

    public static function get()
    {
        if (! self::$di) {
            self::$di = new Di();
        }
        return self::$di;
    }

    public function config()
    {
        $config = include 'config.php';
        return $config;
    }

    public function db()
    {
        $config = $this->config();
        try {
            $db = new PDO(
                'mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8',
                $config['user'],
                $config['pass']
            );
        } catch (PDOException $e) {
            die('Database error: '.$e->getMessage().'<br/>');
        }
        return $db;
    }

    public function render($template, $params = [])
    {
        $fileTemplate = 'template/'.$template;
        if (is_file($fileTemplate)) {
            ob_start();
            if (count($params) > 0) {
                extract($params);
            }
            include $fileTemplate;
            echo ob_get_clean();
        }
    }
}

include 'router/router.php';
