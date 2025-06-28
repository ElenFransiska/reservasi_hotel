<?php

session_start();
require_once '../config/config.php';
require_once '../app/models/Room.php';

class RoomController {
    private $roomModel;

    public function __construct($db) {
        $this->roomModel = new Room($db);
    }

    public function listRooms() {
        $rooms = $this->roomModel->getAllRooms();
        require '../app/views/rooms.php';
    }

    public function availableRooms() {
        $rooms = $this->roomModel->getAvailableRooms();
        require '../app/views/rooms.php';
    }
}
?>
