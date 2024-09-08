<?php
namespace App;

/**
 * SQLite connection
 */
class SQLiteConnection {
    /**
     * PDO instance
     * @var \PDO
     */
    private $pdo;

    /**
     * Return an instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect() {
        if ($this->pdo == null) {
            // Corrected the way to access the constant
            $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
        }
        return $this->pdo;
    }
}