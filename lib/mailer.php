<?php
declare(strict_types=1);

/**
 * send_site_mail - attempts to send mail via PHPMailer using Gmail SMTP.
 * Credentials are loaded from environment variables (set via .env.local).
 */
function send_site_mail(string $to, string $subject, string $body, string $fromEmail = 'info@houseandhomesintoronto.com', string $fromName = 'House and Homes Toronto', bool $isHTML = false)
{
    // Load PHPMailer manually
    require_once __DIR__ . '/PHPMailer/Exception.php';
    require_once __DIR__ . '/PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/SMTP.php';

    // Load env if not already loaded
    require_once __DIR__ . '/ddf.php';

    // Get SMTP credentials from environment variables
    $smtpUser = getenv('SMTP_USER') ?: ($_ENV['SMTP_USER'] ?? 'houseandhomesintoronto@gmail.com');
    $smtpPass = getenv('SMTP_PASS') ?: ($_ENV['SMTP_PASS'] ?? '');
    $smtpHost = getenv('SMTP_HOST') ?: ($_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
    $smtpPort = (int)(getenv('SMTP_PORT') ?: ($_ENV['SMTP_PORT'] ?? 465));

    if ($smtpPass === '') {
        error_log('SMTP_PASS is not set in environment variables');
        return 'Mail configuration error: SMTP credentials missing';
    }

    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // Port 465 uses SMTPS
        $mail->Port = $smtpPort;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($smtpUser, $fromName);
        $mail->addAddress($to);
        if ($fromEmail && $fromEmail !== 'info@houseandhomesintoronto.com') {
            $mail->addReplyTo($fromEmail, $fromName);
        }
        $mail->Subject = $subject;
        $mail->isHTML($isHTML);
        $mail->Body = $body;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));
        
        $mail->send();
        return true;
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        $error = $mail->ErrorInfo ?: $e->getMessage();
        error_log('PHPMailer send error: ' . $error);
        return $error;
    } catch (\Exception $e) {
        error_log('PHPMailer send error: ' . $e->getMessage());
        return $e->getMessage();
    }
}
