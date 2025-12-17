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
