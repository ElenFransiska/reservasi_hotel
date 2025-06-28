<?php

session_start();
require_once '../config/config.php';
require_once '../app/models/Reservation.php';

class ReservationController {
    private $reservationModel;

    public function __construct($db) {
        $this->reservationModel = new Reservation($db);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $roomId = $_POST['room_id'];
            $checkIn = $_POST['check_in'];
            $checkOut = $_POST['check_out'];

            if ($this->reservationModel->createReservation($userId, $roomId, $checkIn, $checkOut)) {
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Gagal menyewa kamar.";
                require '../app/views/reservations.php';
            }
        } else {
            require '../app/views/reservations.php';
        }
    }

    public function cancel($reservationId) {
        if ($this->reservationModel->cancelReservation($reservationId)) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Gagal membatalkan penyewaan.";
            require '../app/views/reservations.php';
        }
    }

    public function listUserReservation() {
        $userId = $_SESSION['user_id'];
        $reservations = $this->reservationModel->getUserReservation($userId);
        require '../app/views/reservations.php';
    }
}
?>
