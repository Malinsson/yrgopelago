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

function insertReservation(PDO $database, int $guestId, int $roomType, string $arrivalDate, string $departureDate): void
{
    $insertReservation = $database->prepare("INSERT INTO reservations (guest_id, room_id, arrival_date, departure_date) VALUES (:guest_id, :room_id, :arrival_date, :departure_date)");
    $insertReservation->bindParam(':guest_id', $guestId);
    $insertReservation->bindParam(':room_id', $roomType);
    $insertReservation->bindParam(':arrival_date', $arrivalDate);
    $insertReservation->bindParam(':departure_date', $departureDate);
    $insertReservation->execute();
}

function insertBookedFeatures(PDO $database, int $reservationId, string $feature): void
{
    $insertFeatures = $database->prepare("INSERT INTO booked_features (reservation_id, features) VALUES (:reservation_id, :features)");
    $insertFeatures->bindParam(':reservation_id', $reservationId);
    $insertFeatures->bindParam(':features', $feature);
    $insertFeatures->execute();
}

function roomAvailability(PDO $database, string $roomType, string $arrivalDate, string $departureDate): bool
{
    $checkAvailability = $database->prepare("SELECT COUNT(*) FROM reservations WHERE room_id = :room_id AND ((arrival_date <= :departure_date AND departure_date >= :arrival_date))");
    $checkAvailability->bindParam(':room_id', $roomType);
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
