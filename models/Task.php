<?php
require_once __DIR__ . '/../config/db_connect.php';

class Task {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($userId, $status = null, $search = null) {
        $sql = "SELECT * FROM tasks WHERE user_id = :user_id";
        $params = [':user_id' => $userId];

        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        if ($search) {
            $sql .= " AND title LIKE :search";
            $params[':search'] = "%$search%";
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userId, $title, $description, $deadline) {
    try {
        $sql = "INSERT INTO tasks (user_id, title, description, deadline) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([$userId, $title, $description, $deadline]);

        file_put_contents(__DIR__ . '/../debug_log.txt', 
            "Tentando inserir:\nUserID: $userId\nTitle: $title\nDescription: $description\nDeadline: $deadline\nSucesso: " . ($success ? 'Sim' : 'Não') . "\n", 
            FILE_APPEND
        );

        if (!$success) {
            file_put_contents(__DIR__ . '/../debug_log.txt', print_r($stmt->errorInfo(), true), FILE_APPEND);
        }

        return $success;
    } catch (PDOException $e) {
        file_put_contents(__DIR__ . '/../debug_log.txt', "Erro no create(): " . $e->getMessage() . "\n", FILE_APPEND);
        return false;
    }
    }

    public function update($id, $title, $description, $deadline) {
        $sql = "UPDATE tasks SET title = ?, description = ?, deadline = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$title, $description, $deadline, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id, $newStatus) {
        $sql = "UPDATE tasks SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$newStatus, $id]);
    }

    public function findById($id) {
        $sql = "SELECT * FROM tasks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllByUser($userId, $status = '', $sort = '', $search = '') {
    $query = "SELECT * FROM tasks WHERE user_id = :user_id";

    if ($status) {
        $query .= " AND status = :status";
    }

    if ($search) {
        $query .= " AND title LIKE :search";
    }

    
    switch ($sort) {
        case 'oldest':
            $query .= " ORDER BY created_at ASC";
            break;
        case 'deadline':
            $query .= " ORDER BY deadline ASC";
            break;
        default:
            $query .= " ORDER BY created_at DESC";
    }

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    if ($status) $stmt->bindParam(':status', $status);
    if ($search) $stmt->bindValue(':search', "%$search%");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
