<?php

declare(strict_types=1);

require_once __DIR__ . '/app/autoload.php';


//Variabels that can be used in index.php and other files
$returningGuestDiscount = 1;
$minimumBookingForDiscount = 3;


// Common functions used across the application
function clean(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
};

function toUppercase(string $data): string
{
    return ucfirst($data);
}

function toLowercase(string $data): string
{
    return strtolower($data);
}

function returningGuest(PDO $database, string $name): bool
{
    $guests = searchAllGuests($database);
    $previousGuests = array_column($guests, "name");
    return in_array($name, $previousGuests);
}

function getGuestId(PDO $database, string $name): int
{
    $returningGuest = $database->prepare("SELECT id FROM guests WHERE name = :name");
    $returningGuest->bindParam(':name', $name);
    $returningGuest->execute();
    $guestData = $returningGuest->fetch(PDO::FETCH_ASSOC);
    return (int)$guestData['id'];
}

function getRoomId(string $roomType): ?int
{
    if ($roomType === "budget") {
        return 1;
    } elseif ($roomType === "premium") {
        return 2;
    } elseif ($roomType === "luxury") {
        return 3;
    } else {
        return null;
    }
}

function calculateDays(string $arrivalDate, string $departureDate): int
{
    $arrival = new DateTime($arrivalDate);
    $departure = new DateTime($departureDate);
    $interval = $arrival->diff($departure);
    return (int)$interval->format('%a');
}
