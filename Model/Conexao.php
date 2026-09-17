<?php
namespace Model;

require_once __DIR__ . "/../Config/configuration.php";

use PDO;
use PDOException;

class Conexao
{
    private static $stmt;

    public static function getInstance(): PDO
    {
        try {
            if (empty(self::$stmt)) {
                self::$stmt = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . "", DB_USER, DB_PASSWORD);
            }
        } catch (PDOException $error) {
            die("Erro na conexão" . $error->getMessage());
        }

        return self::$stmt;
    }

    public static function get(): PDO
    {
        return self::getInstance();
    }
}

if (!class_exists('Conexao', false)) {
    class_alias('Model\\Conexao', 'Conexao');
}
