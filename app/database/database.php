<?php

declare(strict_types=1);

$database = new PDO('sqlite:' . __DIR__ . '/hotel.db');

function searchAllRooms(PDO $database): array
{
    $rooms = $database->query('SELECT * FROM rooms');
    return $rooms->fetchAll(PDO::FETCH_ASSOC);
}

function searchAllFeatures(PDO $database): array
{
    $features = $database->query('SELECT * FROM features');
    return $features->fetchAll(PDO::FETCH_ASSOC);
}

function searchAllGuests(PDO $database): array
{
    $guests = $database->query('SELECT * FROM guests');
    return $guests->fetchAll(PDO::FETCH_ASSOC);
}

function searchAllReservations(PDO $database): array
{
    $reservations = $database->query('SELECT * FROM reservations');
    return $reservations->fetchAll(PDO::FETCH_ASSOC);
}

function searchBookedDates(PDO $database): array
{
    $bookedDates = $database->query('SELECT room_id, arrival_date, departure_date FROM reservations where room_id is not null');
    return $bookedDates->fetchAll(PDO::FETCH_ASSOC);
}

function insertReservation(PDO $database, int $guestId, int $roomType, string $arrivalDate, string $departureDate): void
{
    $insertReservation = $database->prepare("INSERT INTO reservations (guest_id, room_id, arrival_date, departure_date) VALUES (:guest_id, :room_id, :arrival_date, :departure_date)");
    $insertReservation->bindParam(':guest_id', $guestId);
    $insertReservation->bindParam(':room_id', $roomType);
    $insertReservation->bindParam(':arrival_date', $arrivalDate);
    $insertReservation->bindParam(':departure_date', $departureDate);
    $insertReservation->execute();
}

function insertBookedFeatures(PDO $database, int $reservationId, int $featureId, int $price): void
{
    $insertFeatures = $database->prepare("INSERT INTO booked_features (reservation_id, feature_id, price) VALUES (:reservation_id, :feature_id, :price)");
    $insertFeatures->bindParam(':reservation_id', $reservationId);
    $insertFeatures->bindParam(':feature_id', $featureId);
    $insertFeatures->bindParam(':price', $price);
    $insertFeatures->execute();
}

function insertPayment(PDO $database, int $reservationId, int $totalSum, string $transferCode, string $paymentStatus): void
{
    $insertPayment = $database->prepare("INSERT INTO payments (reservation_id, total_sum, transfer_code, payment_status) VALUES (:reservation_id, :total_sum, :transfer_code, :payment_status)");
    $insertPayment->bindParam(':reservation_id', $reservationId);
    $insertPayment->bindParam(':total_sum', $totalSum);
    $insertPayment->bindParam(':transfer_code', $transferCode);
    $insertPayment->bindParam(':payment_status', $paymentStatus);
    $insertPayment->execute();
}

function roomAvailability(PDO $database, int $roomId, string $arrivalDate, string $departureDate): bool
{
    $checkAvailability = $database->prepare("SELECT COUNT(*) FROM reservations WHERE room_id = :room_id AND ((arrival_date < :departure_date AND departure_date >= :arrival_date))");
    $checkAvailability->bindParam(':room_id', $roomId);
    $checkAvailability->bindParam(':arrival_date', $arrivalDate);
    $checkAvailability->bindParam(':departure_date', $departureDate);
    $checkAvailability->execute();
    $count = $checkAvailability->fetchColumn();

    return $count == 0;
}

function getRoomPrice(PDO $database, int $roomId): int
{
    $getPrice = $database->prepare("SELECT price_per_night FROM rooms WHERE id = :id");
    $getPrice->bindParam(':id', $roomId);
    $getPrice->execute();
    $priceData = $getPrice->fetch(PDO::FETCH_ASSOC);
    return (int)$priceData['price_per_night'];
}

function insertGuest(PDO $database, string $name): void
{
    $insertGuest = $database->prepare("INSERT INTO guests (name) VALUES (:name)");
    $insertGuest->bindParam(':name', $name);
    $insertGuest->execute();
}

function changeRoomPrices(PDO $database, int $budgetPrice, int $standardPrice, int $luxuryPrice): void
{
    $updatePrices = $database->prepare("UPDATE rooms SET price_per_night = CASE id 
        WHEN 1 THEN :budget 
        WHEN 2 THEN :standard 
        WHEN 3 THEN :luxury
        END");
    $updatePrices->bindParam(':budget', $budgetPrice);
    $updatePrices->bindParam(':standard', $standardPrice);
    $updatePrices->bindParam(':luxury', $luxuryPrice);
    $updatePrices->execute();
}
