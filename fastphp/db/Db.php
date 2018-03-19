<?php
namespace fastphp\db;

use PDO;
use PDOException;

/**
 * 数据库操作类。
 * 其$pdo属性为静态属性，所以在页面执行周期内，
 * 只要一次赋值，以后的获取还是首次赋值的内容。
 * 这里就是PDO对象，这样可以确保运行期间只有一个
 * 数据库连接对象，这是一种简单的单例模式
 * Class Db
 */
class Db
{
    private static $pdo = null;
    public static $object = array();
    public static function pdo()
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            $dsn    = sprintf('mysql:host=%s;dbname=%s;charset=utf8', DB_HOST, DB_NAME); 
            $option = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

            return self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $option);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

   public function obj($ip,$port,$username,$password)
   {
    try {
            $dsn    = sprintf('mysql:host=%s;dbname=%s;charset=utf8',$ip, DB_NAME); 
            $option = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

            $pdo = new PDO($dsn, $username, $password, $option);
        } catch (PDOException $e) {
            exit('数据库连接失败');
        }

        self::$object[$_SERVER['REMOTE_ADDR']] = $pdo;
        return true;
   }
}