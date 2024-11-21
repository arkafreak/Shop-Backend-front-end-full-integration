<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database handler
    private $stmt; // Statement handler
    private $error; // Error message

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            throw new Exception("Database connection error: " . $this->error);
        }
    }

    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        try {
            error_log("Executing SQL: " . $this->stmt->queryString);
            return $this->stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Query Error: " . $e->getMessage());
            throw new Exception("Error executing query: " . $e->getMessage());
        }
    }
    // Prepare a statement
    public function prepare($query)
    {
        $this->stmt = $this->dbh->prepare($query);
        return $this->stmt;
    }
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function fetchColumn()
    {
        $this->execute();
        return $this->stmt->fetchColumn();
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));
        $this->query("INSERT INTO $table ($columns) VALUES ($placeholders)");

        $this->bindParams($this->stmt, $data);
        return $this->execute();
    }

    public function update($table, $data, $where)
    {
        $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $this->query("UPDATE $table SET $set WHERE $where");

        $this->bindParams($this->stmt, $data);
        return $this->execute();
    }

    public function delete($table, $where)
    {
        $this->query("DELETE FROM $table WHERE $where");
        return $this->execute();
    }

    public function select($table, $columns = '*', $where = '')
    {
        $query = "SELECT $columns FROM $table" . ($where ? " WHERE $where" : "");
        $this->query($query);
        return $this->resultSet();
    }

    private function bindParams($stmt, $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                throw new Exception("Invalid value for parameter $key: expected a string or scalar value, but received an array.");
            }
            $stmt->bindValue(":$key", $value);
        }
    }

    // Start a transaction
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    // Commit the transaction
    public function commit()
    {
        return $this->dbh->commit();
    }

    // Rollback the transaction
    public function rollBack()
    {
        return $this->dbh->rollBack();
    }
}
