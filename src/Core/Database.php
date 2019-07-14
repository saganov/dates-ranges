<?php

namespace DateRange\Core;

use Exception;
use mysqli;
use mysqli_result;

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
     * @return bool|array
     * @throws Exception
     */
    public function query($sql)
    {
        $result = $this->connection()->query($sql);
        if (false === $result) {
            throw new Exception('Mysql error: '. $this->connection()->error);
        }
        $ret = true;
        if ($result instanceof mysqli_result) {
            $ret = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
        }
        return $ret;
    }

    /**
     * @return mysqli
     * @throws Exception
     */
    private function connection(): mysqli
    {
        if (!$this->connection) {
            $this->connection = new mysqli($this->host(), $this->user(), $this->password(), $this->database());
            if ($this->connection->connect_errno) {
                echo "Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error;
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

    /**
     * Close the MySQL connection if it is opened
     */
    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}