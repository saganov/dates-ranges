<?php

namespace DateRange\Core;

use Exception;
use mysqli;
use PDO;
use PDOException;

class Database
{
    /** @var string */
    private $url;

    /** @var mysqli */
    private $connection;

    /**
     * Database constructor.
     * @param string $url in the following format mysql://user:password@hostname:port/database
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param $sql
     * @param array $placeholders
     * @return bool|array
     * @throws Exception
     */
    public function query($sql, $placeholders = [])
    {
        if (!count($placeholders)) {
            $result = $this->connection()->query($sql)->fetchAll();
        } else {
            $stmt = $this->connection()->prepare($sql);
            $stmt->execute($placeholders);
            $result = $stmt->fetchAll();
        }
        if (false === $result) {
            throw new Exception('Mysql error: '. $this->connection()->error . '. SQL: '. $sql);
        }
        return $result;
    }

    /**
     * @return PDO
     * @throws Exception
     */
    private function connection(): PDO
    {
        if (!$this->connection) {
//            $this->connection = new mysqli($this->host(), $this->user(), $this->password(), $this->database());
//            if ($this->connection->connect_errno) {
//                echo "Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error;
//            }
            $dsn = "mysql:host={$this->host()};dbname={$this->database()};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                $this->connection = new PDO($dsn, $this->user(), $this->password(), $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return $this->connection;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function host(): string
    {
        $host = $this->urlComponent(PHP_URL_HOST);
        if (!$host) {
            throw new Exception('MySQL host must be specified');
        }
        return $host;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function user(): string
    {
        $user = $this->urlComponent(PHP_URL_USER);
        if (!$user) {
            throw new Exception('MySQL user must be specified');
        }
        return $user;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function password(): string
    {
        return $this->urlComponent(PHP_URL_PASS);
    }

    /**
     * @return string
     * @throws Exception
     */
    private function database(): string
    {
        $database = $this->urlComponent(PHP_URL_PATH);
        if (!$database) {
            throw new Exception('MySQL database must be specified');
        }
        return trim($database, '/');
    }

    /**
     * @param int $component
     * @return string|int
     * @throws Exception
     */
    private function urlComponent(int $component)
    {
        $component = parse_url($this->url, $component);
        if (false === $component) {
            throw new Exception('Unable to parse MySQL url string');
        }
        return $component;
    }
}