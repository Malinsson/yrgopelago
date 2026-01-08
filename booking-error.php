<?php

require_once __DIR__ . '/views/header.php';

// Check if we have error data in session
if (!isset($_SESSION['bookingError'])) {
    header('Location: index.php');
    exit();
}

$error = $_SESSION['bookingError'];

// Clear the session after displaying
unset($_SESSION['bookingError']);
?>

<main class="error-page">
    <section class="booking-error card">
        <h2 class="card-header">Booking Error</h2>

        <div class="error-message">
            <p><?= htmlspecialchars($error['message']) ?></p>
            <?php if (isset($error['details'])): ?>
                <p><strong>Details:</strong> <?= htmlspecialchars($error['details']) ?></p>
            <?php endif; ?>
        </div>

        <button onclick="window.location.href='index.php'" class="btn-primary">Go Back</button>
    </section>
</main>

<?php require_once __DIR__ . '/views/footer.php'; ?>