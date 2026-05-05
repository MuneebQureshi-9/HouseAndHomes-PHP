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

$subjectLine = $subject !== '' ? $subject : 'Website Contact';
if ($propertyAddress !== '') {
    $subjectLine .= ' - Property Inquiry';
}

// Build professional HTML email
$phoneRow = '';
if ($phone !== '') {
    $phoneRow = '<tr><td style="padding:12px 16px;font-weight:600;color:#1a1a2e;width:140px;border-bottom:1px solid #f0f0f0;">📞 Phone</td><td style="padding:12px 16px;color:#333;border-bottom:1px solid #f0f0f0;">' . htmlspecialchars($phone) . '</td></tr>';
}
$addressRow = '';
if ($propertyAddress !== '') {
    $addressRow = '<tr><td style="padding:12px 16px;font-weight:600;color:#1a1a2e;width:140px;border-bottom:1px solid #f0f0f0;">🏠 Property</td><td style="padding:12px 16px;color:#333;border-bottom:1px solid #f0f0f0;">' . htmlspecialchars($propertyAddress) . '</td></tr>';
}

$body = '
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:24px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
        <!-- Header -->
        <tr><td style="background:linear-gradient(135deg,#1a1a2e,#16213e);padding:28px 32px;text-align:center;">
          <h1 style="margin:0;color:#d4a843;font-size:22px;font-weight:700;">Houses &amp; Homes Toronto</h1>
          <p style="margin:6px 0 0;color:rgba(255,255,255,0.7);font-size:13px;">New Website Inquiry</p>
        </td></tr>
        <!-- Subject Badge -->
        <tr><td style="padding:20px 32px 0;">
          <span style="display:inline-block;background:#d4a843;color:#1a1a2e;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:700;text-transform:uppercase;">' . htmlspecialchars($subjectLine) . '</span>
        </td></tr>
        <!-- Details Table -->
        <tr><td style="padding:20px 32px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e8e8e8;border-radius:8px;overflow:hidden;">
            <tr><td style="padding:12px 16px;font-weight:600;color:#1a1a2e;width:140px;border-bottom:1px solid #f0f0f0;">👤 Name</td><td style="padding:12px 16px;color:#333;border-bottom:1px solid #f0f0f0;">' . htmlspecialchars($name) . '</td></tr>
            <tr><td style="padding:12px 16px;font-weight:600;color:#1a1a2e;width:140px;border-bottom:1px solid #f0f0f0;">✉️ Email</td><td style="padding:12px 16px;color:#333;border-bottom:1px solid #f0f0f0;"><a href="mailto:' . htmlspecialchars($email) . '" style="color:#2563eb;">' . htmlspecialchars($email) . '</a></td></tr>
            ' . $phoneRow . $addressRow . '
          </table>
        </td></tr>
        <!-- Message -->
        <tr><td style="padding:0 32px 24px;">
          <p style="margin:0 0 8px;font-weight:600;color:#1a1a2e;font-size:14px;">💬 Message</p>
          <div style="background:#f8f9fa;border:1px solid #e8e8e8;border-radius:8px;padding:16px;color:#333;font-size:14px;line-height:1.6;">' . nl2br(htmlspecialchars($message)) . '</div>
        </td></tr>
        <!-- Footer -->
        <tr><td style="background:#f8f9fa;padding:16px 32px;text-align:center;border-top:1px solid #e8e8e8;">
          <p style="margin:0;color:#999;font-size:11px;">This message was sent from the Houses &amp; Homes Toronto website contact form.</p>
        </td></tr>
      </table>
    </td></tr>
  </table>
</body>
</html>';

$sent = send_site_mail($to, $subjectLine, $body, $email, $name, true);

if ($sent === true) {
    echo json_encode(['success' => true, 'message' => 'Message sent']);
} else {
    http_response_code(500);
    // return the actual error string so the frontend displays why it failed
    echo json_encode(['success' => false, 'message' => "Failed to send message: " . (is_string($sent) ? $sent : 'Unknown error')]);
}
