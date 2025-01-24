<?php

/**
 *
 */
class ORM {
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @param $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     *
     * Insert Data
     *
     * @param $table
     * @param $data
     * @return void
     * @throws Exception
     */
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    /**
     *
     * Update Data
     *
     * @param $table
     * @param $data
     * @param $where
     * @return void
     * @throws Exception
     */
    public function update($table, $data, $where) {
        // Validate columns exist in the table before proceeding
        $this->validateColumns($table, array_merge(array_keys($data), array_keys($where)));

        $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));
        $sql = "UPDATE $table SET $set WHERE $whereClause";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge($data, $where));
    }

    /**
     *
     * Delete Data
     *
     * @param $table
     * @param $where
     * @return void
     * @throws Exception
     */
    public function delete($table, $where) {
        // Validate columns exist in the table before proceeding
        $this->validateColumns($table, array_keys($where));

        $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));
        $sql = "DELETE FROM $table WHERE $whereClause";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($where);
    }

    /**
     * Validate that columns exist in the table
     *
     * @param string $table
     * @param array $columns
     * @throws Exception
     */
    private function validateColumns($table, $columns) {
        $columnsString = implode(",", $columns);

        // Get the column names from the database schema
        $sql = "DESCRIBE $table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $tableColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Check if all specified columns exist in the table
        foreach ($columns as $column) {
            if (!in_array($column, $tableColumns)) {
                throw new Exception("Column '$column' does not exist in table '$table'.");
            }
        }
    }
}