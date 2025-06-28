<?php

class Room {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllRooms() {
        $stmt = $this->db->prepare("SELECT * FROM rooms");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableRooms() {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE status = 'available'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRoomStatus($roomId, $status) {
        $stmt = $this->db->prepare("UPDATE rooms SET status = ? WHERE id = ?");
        $stmt->execute([$status, $roomId]);
    }
}
?>
