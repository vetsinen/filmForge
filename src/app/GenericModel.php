<?php

namespace Webdev\Filmforge;

require_once(__DIR__.'/../config.php');

class GenericModel
{
    private $connection;

    public function __construct()
    {
        $this->connection = new \mysqli(MYSQL_ROOT_HOST, MYSQL_USER, MYSQL_USER, MYSQL_DATABASE);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function fetch($sql)
    {
        $iterator = $this->connection->query($sql);
        $rez = [];
        while ($row = $iterator->fetch_assoc()) {
            $rez[] = $row;
        }
        return $rez;
    }

    public function insertAndProvideId($sql)
    {
        if ($this->connection->query($sql) === TRUE) {
            $last_inserted_id = $this->connection->insert_id;
            error_log("New record created successfully. ID is: " . $last_inserted_id);
            return $last_inserted_id;
        } else {
            error_log("Error: " . $sql . "<br>" . $this->connection->error);
            return 0;
        }
    }

    public function __invoke($sql)
    {
        return $this->fetch($sql);
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function execute($sql)
    {
        return $this->connection->query($sql);
    }

    public function close()
    {
        $this->connection->close();
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}