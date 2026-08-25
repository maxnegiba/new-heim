<?php
header('Content-Type: application/json');

$name    = trim($_POST['name']    ?? '');
$phone   = trim($_POST['phone']   ?? '');
$email   = trim($_POST['email']   ?? '');
$addr    = trim($_POST['address'] ?? '');
$service = $_POST['service']      ?? '';
$date    = $_POST['date']         ?? '';
$msg     = trim($_POST['message'] ?? '');
$website = $_POST['website']      ?? '';

if ($website !== '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Spam detected']);
    exit;
}

$recaptcha = $_POST['recaptcha_response'] ?? '';
$secret = getenv('RECAPTCHA_SECRET');

if (!$secret) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Captcha configuration missing']);
    exit;
}

$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $verifyUrl,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query(['secret' => $secret, 'response' => $recaptcha]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
]);
$response = json_decode(curl_exec($ch) ?: '');
curl_close($ch);

if (!$response || !$response->success || ($response->score ?? 0) < 0.5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Captcha failed']);
    exit;
}

if ($name === '' || $addr === '' || $service === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Pflichtfelder fehlen']);
    exit;
}

if ($phone === '' && $email === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Telefon oder E-Mail angeben']);
    exit;
}

$subject = 'Neue Kontaktanfrage - Dachdecker Berlin 24';
$body = "Name: $name\nAdresse: $addr\nTelefon: $phone\nE-Mail: $email\nDienstleistung: $service\nWunschtermin: $date\n\nNachricht:\n$msg";

$mailTo = getenv('MAIL_TO') ?: 'info@dachdeckerberlin24.de';
$mailFrom = getenv('MAIL_FROM') ?: 'website@dachdeckerberlin24.de';
$replyTo = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : $mailFrom;

$headers =
    "From: Dachdecker Berlin 24 <$mailFrom>\r\n" .
    "Reply-To: $replyTo\r\n" .
    "Content-Type: text/plain; charset=utf-8\r\n";

$sent = mail($mailTo, $subject, $body, $headers);

if (!$sent) {
    http_response_code(500);
}

echo json_encode(['success' => (bool) $sent]);
