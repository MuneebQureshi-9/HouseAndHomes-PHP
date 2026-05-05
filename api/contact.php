<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
if (strpos($contentType, 'application/json') !== false) {
    $content = trim(file_get_contents('php://input'));
    $decoded = json_decode($content, true);
    if (is_array($decoded)) {
        $_POST = array_merge($_POST, $decoded);
    }
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
$propertyAddress = trim((string)($_POST['propertyAddress'] ?? ''));
$propertyAddress = trim((string)($_POST['address'] ?? $propertyAddress)); // fallback to address field

if ($name === '' || $email === '' || $message === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please provide name, email and message.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

require_once __DIR__ . '/../lib/mailer.php';

$to = 'houseandhomesintoronto@gmail.com';
$mailSubject = 'Website Contact';
if ($subject !== '') {
    $mailSubject .= ' - ' . $subject;
}

$body = "Name: $name\nEmail: $email\n";
if ($phone !== '') {
    $body .= "Phone: $phone\n";
}
$subjectLine = $subject !== '' ? $subject : 'Website Contact';
if ($propertyAddress !== '') {
    $subjectLine .= ' - Property Inquiry';
    $body .= "Property Address: $propertyAddress\n";
}
$body .= "\nMessage:\n$message\n";

$sent = send_site_mail($to, $subjectLine, $body, $email, $name);

if ($sent === true) {
    echo json_encode(['success' => true, 'message' => 'Message sent']);
} else {
    http_response_code(500);
    // return the actual error string so the frontend displays why it failed
    echo json_encode(['success' => false, 'message' => "Failed to send message: " . (is_string($sent) ? $sent : 'Unknown error')]);
}
