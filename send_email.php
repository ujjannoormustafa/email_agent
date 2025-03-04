<?php
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$cryptoData = $data['cryptoData'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Prepare email content
$subject = "Crypto Price Updates";
$message = "Current Crypto Prices:\r\n\r\n";

foreach ($cryptoData as $crypto => $price) {
    $priceChange = number_format($price['usd_24h_change'], 2);
    $message .= strtoupper($crypto) . ":\r\n";
    $message .= "Price: $" . number_format($price['usd'], 2) . "\r\n";
    $message .= "24h Change: " . $priceChange . "%\r\n\r\n";
}

// Replace with your domain email
$from_email = "noreply@yourdomain.com";

$headers = "From: Crypto Price Tracker <{$from_email}>\r\n";
$headers .= "Reply-To: {$from_email}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
$mailSent = mail($email, $subject, $message, $headers);

// Return result
echo json_encode(['success' => $mailSent]);
?> 