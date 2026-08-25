<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Methode nicht erlaubt.']);
    exit;
}

function field(string $name, int $max = 500): string
{
    $value = trim((string) ($_POST[$name] ?? ''));
    $value = str_replace(["\r", "\0"], '', $value);
    return mb_substr($value, 0, $max);
}

$name = field('name', 120);
$phone = field('phone', 40);
$email = field('email', 160);
$address = field('address', 220);
$service = field('service', 120);
$date = field('date', 20);
$message = field('message', 3000);
$website = field('website', 200);
$consent = (string) ($_POST['consent'] ?? $_POST['gdpr'] ?? '');
$startedAt = (int) ($_POST['started_at'] ?? 0);

if ($website !== '' || ($startedAt > 0 && time() - $startedAt < 2)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Anfrage konnte nicht verarbeitet werden.']);
    exit;
}

$consentGiven = in_array(strtolower($consent), ['1', 'on', 'yes', 'true'], true);
if ($name === '' || $address === '' || $service === '' || !$consentGiven) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Bitte füllen Sie alle Pflichtfelder aus.']);
    exit;
}

if ($phone === '' && $email === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Bitte geben Sie Telefon oder E-Mail an.']);
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Bitte geben Sie eine gültige E-Mail-Adresse an.']);
    exit;
}

$recipient = getenv('CONTACT_TO_EMAIL') ?: 'info.michaell.gmbh@gmail.com';
$subject = 'Neue Anfrage über dachdeckerberlin24.de';
$body = implode("\n", [
    'Neue Anfrage über dachdeckerberlin24.de',
    '----------------------------------------',
    "Name: {$name}",
    "Telefon: {$phone}",
    "E-Mail: {$email}",
    "Objekt: {$address}",
    "Leistung: {$service}",
    "Wunschtermin: {$date}",
    '',
    'Nachricht:',
    $message,
]);

$headers = [
    'From: MB Bau Website <website@dachdeckerberlin24.de>',
    'Content-Type: text/plain; charset=UTF-8',
    'MIME-Version: 1.0',
];
if ($email !== '') {
    $headers[] = 'Reply-To: ' . $email;
}

$sent = @mail($recipient, $subject, $body, implode("\r\n", $headers));

if (!$sent) {
    error_log('MB Bau contact form: mail() returned false');
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Die Anfrage konnte gerade nicht per E-Mail gesendet werden. Bitte rufen Sie uns an oder nutzen Sie WhatsApp.']);
    exit;
}

echo json_encode(['success' => true]);
