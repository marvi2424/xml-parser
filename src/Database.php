<?php

/**
 * Handles the database connection using PDO.
 */
class Database {
    /**
     * @var PDO The PDO instance used for database interactions.
     */
    private PDO $pdo;

    /**
     * Initializes the database connection using configuration from a file.
     */
    public function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        $this->pdo = new PDO($dsn, $config['username'], $config['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Gets the PDO connection instance.
     *
     * @return PDO The active PDO connection.
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}