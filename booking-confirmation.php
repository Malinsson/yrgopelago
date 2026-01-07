<?php
session_start();

require_once __DIR__ . '/views/header.php';

if (!isset($_SESSION['bookingData']) || !$_SESSION['bookingData']['success']) {
    header('Location: index.php');
    exit();
}

$bookingData = $_SESSION['bookingData'];
$receipt = $bookingData['receipt'];
$receiptResponse = $bookingData['receiptResponse'];
$totalCost = $bookingData['totalCost'];

// Hide sensitive information
$receiptForDisplay = $receipt;
unset($receiptForDisplay['user']);
unset($receiptForDisplay['api_key']);

unset($_SESSION['bookingData']);
?>

<main>
    <section class="booking-confirmation card">
        <h2 class="card-header">Booking Successful!</h2>

        <div class="confirmation-details">
            <p>Receipt ID:<?= ($receiptResponse['receipt_id']) ?></p>
            <p>Total Cost: $<?= $totalCost ?></p>
        </div>

        <h3>Receipt Details:</h3>
        <pre class="receipt-json"><?php echo json_encode($receiptForDisplay, JSON_PRETTY_PRINT); ?></pre>

        <button onclick="window.location.href='index.php'" class="btn-primary">Go Back Home</button>
    </section>
</main>

<?php require_once __DIR__ . '/views/footer.php'; ?>