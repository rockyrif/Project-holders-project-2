<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '/home2/adtennis/public_html/PHP-mailer/vendor/autoload.php'; // Replace '/path/to/vendor/autoload.php' with the absolute path to your autoload.php file

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debug output for cron job
    $mail->isSMTP(); // Send using SMTP
    $mail->Host       = 'mail.adtennis.lk'; // Set the SMTP server to send through
    $mail->SMTPAuth   = true; // Enable SMTP authentication
    $mail->Username   = 'admin@adtennis.lk'; // SMTP username
    $mail->Password   = '2l01xVKb:EO.9p'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable explicit TLS encryption
    $mail->Port       = 465;

    // Set custom CA certificates to trust the self-signed certificate
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Include the database connection file
    require '/home2/adtennis/public_html/project-holders-project-2/db_conn.php'; // Replace '/path/to/project-holders-project-2/db_conn.php' with the absolute path to your db_conn.php file

    // Get current month and year
    $current_month = date('m');
    $current_year = date('Y');

    // SQL query to select members who haven't paid for the current month and year
    $sql = "SELECT DISTINCT m.email, m.first_name
    FROM members m
    LEFT JOIN member_fees mf ON m.member_id = mf.member_id
    WHERE mf.member_id IS NULL OR (mf.month <> $current_month OR mf.year <> $current_year)
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Loop through the results and send emails
        while ($row = $result->fetch_assoc()) {
            $mail->clearAddresses();
            $mail->addAddress($row['email'], $row['first_name']);
            $first_name = $row['first_name'];
            $mail->setFrom('admin@adtennis.lk', 'AD tennis admin');
            $mail->Subject = "Reminder: Payment Due";
            $mail->Body = "Dear $first_name,\n\nThis is a reminder that your membership fee for the current month is due.\n\nBest regards,\nADTC";

            // Send email
            $mail->send();
            // Do not echo here for cron job
        }

    } else {
        echo "No members found.";
    }

    // Close connection
    $conn->close();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
