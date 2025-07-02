<?php

class Room {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllRooms() {
        $stmt = $this->db->prepare("SELECT * FROM kamar");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableRooms() {
        $stmt = $this->db->prepare("SELECT * FROM kamar WHERE status = 'available'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRoomStatus($roomId, $status) {
        $stmt = $this->db->prepare("UPDATE kamar SET status = ? WHERE id = ?");
        $stmt->execute([$status, $roomId]);
    }
}
?>
