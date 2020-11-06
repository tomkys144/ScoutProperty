<?php


namespace App\Services;

use PDO;
use PDOException;

require dirname(__DIR__) . '/../bootstrap.php';

/**
 * Class DatabaseService
 * @package App\Services
 */
class DatabaseService
{
    /**
     * @var PDO|null
     */
    private ?PDO $databaseConnection = null;

    /**
     * DatabaseService constructor.
     */
    public function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $this->databaseConnection = new PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$dbname",
                $user,
                $password
            );
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @param string $name
     * @param array $data
     * @return bool|null
     */
    public function write(string $name, array $data): ?bool
    {
        if (!$this->tableExists($name)) {
            $this->createTable($name, $data);
        }
        $values = '(';
        $keys = '(';
        foreach ($data as $key => $value) {
            $values .= $value . ', ';
            $keys .= $key . ', ';
        }

        $values = substr($values, 0, -2) . ')';
        $keys = substr($keys, 0, -2) . ')';

        $statement = "INSERT INTO $name $keys" . PHP_EOL . "VALUES $values;";

        try {
            $this->databaseConnection->exec($statement);
            return true;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    private function tableExists(string $name): ?bool
    {
        if ($result = $this->databaseConnection->exec("SHOW TABLES LIKE '" . $name . "'")) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @param array $columns
     * @return bool|null
     */
    private function createTable(string $name, array $columns): ?bool
    {
        $params = '(' . PHP_EOL . 'id INT NOT NULL AUTO_INCREMENT,' . PHP_EOL;
        $no_columns = count($columns);
        $i = 0;
        foreach ($columns as $key => $value) {
            if ($key !== 'id') {
                if (++$i !== $no_columns) {
                    $params .= $key . ' ' . gettype($value) . ',' . PHP_EOL;
                } else {
                    $params .= $key . ' ' . gettype($value) . PHP_EOL;
                }
            }
        }
        $statement = "CREATE TABLE IF NOT EXISTS $name $params)";
        try {
            $this->databaseConnection->exec($statement);
            return true;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @param string $table
     * @param array|string[] $columns
     * @param string|null $condition
     * @return array
     */
    public function getData(string $table, array $columns = ['*'], string $condition = null): ?array
    {
        $statement = "SELECT ";
        foreach ($columns as $column) {
            $statement .= $column . ", ";
        }
        $statement = substr_replace($statement, ' ', -2);
        $statement .= "FROM $table";
        if (isset($condition)) {
            $statement .= PHP_EOL . "WHERE $condition;";
        }
        try {
            $response = $this->databaseConnection->query($statement);
            return $response->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}