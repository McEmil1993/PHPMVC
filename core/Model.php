<?php
class Model {
    public $db;
    public $tablename;
    public $columns;

    public function __construct($db, $tablename, $columns) {
        $this->db = $db;
        $this->tablename = $tablename;
        $this->columns = $columns;
    }

    public function create($userData) {
        $columnNames = implode(", ", array_keys($userData));
        $columnValues = ":" . implode(", :", array_keys($userData));
        $query = "INSERT INTO $this->tablename ($columnNames) VALUES ($columnValues)";
        $stmt = $this->db->prepare($query);
        foreach ($userData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function read($userId) {
        $query = "SELECT * FROM $this->tablename WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($userId, $newData) {
        $updateValues = "";
        foreach ($newData as $key => $value) {
            $updateValues .= "$key=:$key, ";
        }
        $updateValues = rtrim($updateValues, ", ");
        $query = "UPDATE $this->tablename SET $updateValues WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        foreach ($newData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function delete($userId) {
        $query = "DELETE FROM $this->tablename WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function changeStatus($userId, $newStatus) {
        $query = "UPDATE $this->tablename SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function join($table, $condition) {
        $this->tablename .= " JOIN $table ON $condition";
        return $this;
    }

    public function leftJoin($table, $condition) {
        $this->tablename .= " LEFT JOIN $table ON $condition";
        return $this;
    }

    public function rightJoin($table, $condition) {
        $this->tablename .= " RIGHT JOIN $table ON $condition";
        return $this;
    }
}

?>