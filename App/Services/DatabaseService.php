<?php


namespace App\Services;

use mysqli;

require dirname(__DIR__) . '/../bootstrap.php';

/**
 * Class DatabaseService
 * @package App\Services
 */
class DatabaseService
{
    private ?mysqli $databaseConnection = null;

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


        $this->databaseConnection = new mysqli($host, $user, $password, $dbname, $port);
        if ($this->databaseConnection->connect_error) {
            exit($this->databaseConnection->connect_error);
        }
    }

    public function __destruct()
    {
        $this->databaseConnection->close();
    }

    /**
     * @param string $name
     * @param array $data
     * @return array
     */
    public function write(string $name, array $data): array
    {
        if (!$this->tableExists($name)) {
            $result = $this->createTable($name, $data);
            if (!$result['SUCCESS']) {
                return $result;
            }
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

        if ($this->databaseConnection->query($statement) === TRUE) {
            return array('SUCCESS' => true);
        }

        return array('SUCCESS' => false, 'ERROR_CODE' => $this->databaseConnection->errno, 'ERROR_MESSAGE' => $this->databaseConnection->error);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function tableExists(string $name): ?bool
    {
        $result = $this->databaseConnection->query("SHOW TABLES LIKE '" . $name . "'");
        return $result->num_rows > 0;
    }

    /**
     * @param string $name
     * @param array $columns
     * @return bool[]
     */
    private function createTable(string $name, array $columns): array
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

        if ($this->databaseConnection->query($statement) === TRUE) {
            return array('SUCCESS' => true);
        }

        return array('SUCCESS' => false, 'ERROR_CODE' => $this->databaseConnection->errno, 'ERROR_MESSAGE' => $this->databaseConnection->error);
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

        $answer = $this->databaseConnection->query($statement);

        if ($this->databaseConnection->errno) {
            return array('SUCCESS' => false, 'ERROR_CODE' => $this->databaseConnection->errno, 'ERROR_MESSAGE' => $this->databaseConnection->error);
        }

        $result = array();
        while ($row = $answer->fetch_assoc()) {
            $result[] = $row;
        }

        $answer->free();

        return array('SUCCESS' => true, 'DATA' => $result);
    }

    /**
     * @param string $table
     * @param int $id
     * @return array
     */
    public function deleteData(string $table, int $id): array
    {
        $statement = "DELETE FROM $table WHERE id=$id";

        if ($this->databaseConnection->query($statement) === TRUE) {
            return array('SUCCESS' => true);
        }

        return array('SUCCESS' => false, 'ERROR_CODE' => $this->databaseConnection->errno, 'ERROR_MESSAGE' => $this->databaseConnection->error);
    }
}