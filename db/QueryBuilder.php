<?php 
require_once 'DB.php';

class QueryBuilder {
    protected $table;
    protected $where = [];
    protected $bindings = [];

    public function __construct($table) {
        $this->table = $table;
    }

    public function where($column, $value, $operator = '=') {
        $this->where[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function whereOr($column, $value) {
        return $this->where($column, $value, 'OR');
    }

    public function whereIn($column, $values) {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->where[] = "$column IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function whereNull($column) {
        $this->where[] = "$column IS NULL";
        return $this;
    }

    public function whereDate($column, $date) {
        $this->where[] = "DATE($column) = ?";
        $this->bindings[] = $date;
        return $this;
    }

    public function whereNotNull($column) {
        $this->where[] = "$column IS NOT NULL";
        return $this;
    }

    public function whereNotIn($column, $values) {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->where[] = "$column NOT IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function whereAnd($conditions) {
        foreach ($conditions as $column => $value) {
            $this->where($column, $value);
        }
        return $this;
    }

    public function first() {
        $sql = "SELECT * FROM $this->table";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }
        $sql .= " LIMIT 1";

        $stmt = DB::getPdo()->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM $this->table";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        $stmt = DB::getPdo()->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
        $stmt = DB::getPdo()->prepare($sql);
        $stmt->execute(array_values($data));
        return DB::getPdo()->lastInsertId();
    }

    public function update($data) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            $this->bindings[] = $value;
        }

        $sql = "UPDATE $this->table SET " . implode(', ', $set);
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        $stmt = DB::getPdo()->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    public function delete() {
        $sql = "DELETE FROM $this->table";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        $stmt = DB::getPdo()->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    public function join($table, $column1, $operator, $column2) {
        $this->joins[] = "JOIN $table ON $column1 $operator $column2";
        return $this;
    }

    public function leftJoin($table, $column1, $operator, $column2) {
        $this->joins[] = "LEFT JOIN $table ON $column1 $operator $column2";
        return $this;
    }

    public function innerJoin($table, $column1, $operator, $column2) {
        $this->joins[] = "INNER JOIN $table ON $column1 $operator $column2";
        return $this;
    }

    public function rightJoin($table, $column1, $operator, $column2) {
        $this->joins[] = "RIGHT JOIN $table ON $column1 $operator $column2";
        return $this;
    }

    // Other methods like insert, update, delete, join, leftJoin, etc.
}

?>