<?php

namespace App\Admin\Model;

use PDO;
use PDOException;

require_once __DIR__ . '/../../env.php';
loadEnv(__DIR__ . '/../../.env');

class AdminDB
{
    private static ?PDO $pdo = null;

    public static function getPDO(): PDO
    {
        if (self::$pdo === null) {
            $host     = getenv('DB_HOST') ?: '127.0.0.1';
            $port     = getenv('DB_PORT') ?: '5432';
            $dbname   = getenv('DB_NAME') ?: 'etd';
            $user     = getenv('DB_USER') ?: 'postgres';
            $password = getenv('DB_PASS') ?: '';

            try {
                self::$pdo = new PDO(
                    "pgsql:host=$host;port=$port;dbname=$dbname",
                    $user,
                    $password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base Admin : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}