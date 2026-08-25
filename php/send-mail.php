<?php
/* /var/www/site-acoperisuri/mail.php */
header('Content-Type: application/json');

// 1. Datele primite
$name    = trim($_POST['name']    ?? '');
$phone   = trim($_POST['phone']   ?? '');
$email   = trim($_POST['email']   ?? '');
$addr    = trim($_POST['address'] ?? '');
$service = $_POST['service']      ?? '';
$date    = $_POST['date']         ?? '';
$msg     = trim($_POST['message'] ?? '');
$website = $_POST['website']      ?? '';

// 2. Honeypot
if ($website !== '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Spam detected']);
    exit;
}

// 3. reCAPTCHA v3
$recaptcha = $_POST['recaptcha_response'] ?? '';
$secret    = '6Lfd6IwrAAAAAIEybbgYHpw9xrqIo1AY9CPybfwq'; // ← cheia SECRETĂ
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $verifyUrl,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query(['secret' => $secret, 'response' => $recaptcha]),
    CURLOPT_RETURNTRANSFER => true,
]);
$response = json_decode(curl_exec($ch) ?: '');
curl_close($ch);

if (!$response || !$response->success || ($response->score ?? 0) < 0.5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Captcha failed']);
    exit;
}

// 4. Validare
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

// 5. Compunerea e-mailului
$subject = "Neue Kontaktanfrage";
$body    = "Name: $name\nAdresse: $addr\nTelefon: $phone\nE-Mail: $email\nDienstleistung: $service\nWunschtermin: $date\n\nNachricht:\n$msg";

$headers =
    "From: Info.michael@gmbh.de\r\n" .
    "Reply-To: $email\r\n" .
    "Content-Type: text/plain; charset=utf-8\r\n";

// 6. Trimitere
$sent = mail('info.michaell.gmbh@gmail.com', $subject, $body, $headers);

echo json_encode(['success' => (bool)$sent]);
?>