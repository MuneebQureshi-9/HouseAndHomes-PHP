<?php
declare(strict_types=1);

/**
 * send_site_mail - attempts to send mail via PHPMailer using Gmail SMTP.
 */
function send_site_mail(string $to, string $subject, string $body, string $fromEmail = 'info@houseandhomesintoronto.com', string $fromName = 'House and Homes Toronto')
{
    // Load PHPMailer manually
    require_once __DIR__ . '/PHPMailer/Exception.php';
    require_once __DIR__ . '/PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/SMTP.php';

    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'houseandhomesintoronto@gmail.com';
        $mail->Password = 'mnionmdlpfemubjo';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // Port 465 uses SMTPS
        $mail->Port = 465;

        $mail->setFrom('houseandhomesintoronto@gmail.com', $fromName);
        $mail->addAddress($to);
        if ($fromEmail && $fromEmail !== 'info@houseandhomesintoronto.com') {
            $mail->addReplyTo($fromEmail, $fromName);
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        $mail->isHTML(false);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        $error = $mail->ErrorInfo ?: $e->getMessage();
        error_log('PHPMailer send error: ' . $error);
        return $error;
    }
}
