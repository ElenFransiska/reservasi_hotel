<?php
// controllers/ReportController.php

function getMonthlyReservationReport(PDO $pdo, int $year): array
{
    $reportData = [];
    // SQL query to get monthly reservation data
    // This query needs to be correct for your 'reservations' table structure
    $sql = "
        SELECT
            DATE_FORMAT(created_at, '%Y-%m') as year_month,
            DATE_FORMAT(created_at, '%M') as month_name, -- Added for readable month name
            COUNT(reservation_id) as total_reservations,
            SUM(num_guests) as total_guests,
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_reservations,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_reservations,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_reservations,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_reservations
        FROM
            reservations
        WHERE
            YEAR(created_at) = :year
        GROUP BY
            year_month, month_name
        ORDER BY
            year_month;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $reportData;
}
?>