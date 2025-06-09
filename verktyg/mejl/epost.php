<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meddelande = $_POST["meddelande"];

    // Ange sökvägen till PHPMailer-biblioteket
    $phpMailerPath = __DIR__ . '/phpmailer/src/';

    // Kontrollera och inkludera PHPMailer-biblioteket
    $requiredFiles = ['Exception.php', 'PHPMailer.php', 'SMTP.php'];
    foreach ($requiredFiles as $file) {
        $fullPath = $phpMailerPath . $file;
        if (!file_exists($fullPath) || !is_readable($fullPath)) {
            error_log("Error: File {$fullPath} not found or not readable");
            die("Error: File {$fullPath} not found or not readable");
        }
        require $fullPath;
        error_log("Loaded {$fullPath}");
    }

    try {
        // Skapa en instans av PHPMailer
        $mail = new PHPMailer(true);

        // Aktivera detaljerad felloggning
        $mail->SMTPDebug = 2;

        // Ange loggningsfunktionen för PHPMailer
        $mail->Debugoutput = function ($str, $level) {
            error_log("PHPMailer Debug level {$level}: {$str}");
        };

        // Konfigurera SMTP-inställningar
        $mail->isSMTP();
        $mail->Host = 'mail.mackan.eu';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@mackan.eu';
        $mail->Password = 't%b,pliV}=BUiTH';

        // Konfigurera e-postdetaljer
        $mail->setFrom('noreply@mackan.eu', 'Mackan');
        $mail->addAddress('marcus.ornstedt@gmail.com'); // Mottagarens e-postadress
        $mail->Subject = 'Test Email';
        $mail->Body = $meddelande;

        // Försök att skicka e-posten
        if ($mail->send()) {
            echo "E-posten har skickats!";
        } else {
            echo "E-posten kunde inte skickas. Fel: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        error_log("PHPMailer Exception: " . $e->getMessage());
        echo "PHPMailer Exception: " . $e->getMessage();
    }
}
?>
