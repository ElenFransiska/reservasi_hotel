<?php

class reservation {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createReservation($userId, $roomId, $checkIn, $checkOut) {
        $stmt = $this->db->prepare("INSERT INTO reservations (user_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$userId, $roomId, $checkIn, $checkOut])) {
            $roomModel = new Room($this->db);
            $roomModel->updateRoomStatus($roomId, 'booked');
            return true;
        }
        return false;
    }

    public function cancelReservation($reservationId) {
        $stmt = $this->db->prepare("SELECT room_id FROM reservations WHERE id = ?");
        $stmt->execute([$reservationId]);
        $room = $stmt->fetch();

        if ($room) {
            $roomModel = new Room($this->db);
            $roomModel->updateRoomStatus($room['room_id'], 'available');
            $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = ?");
            return $stmt->execute([$reservationId]);
        }
        return false;
    }

    public function getUserReservation($userId) {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
