<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

//fonction pour envoyer un email
function sendMail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

try {
    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV["GMAIL_USER"]; 
    $mail->Password = $_ENV["GMAIL_PASSWORD"]; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinataire et contenu de l'email
    $mail->setFrom($_ENV["GMAIL_USER"], "Michel Ange");
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    // Envoyer l'email
    $mail->send();
} catch (Exception $e) {
    $errorMessage = "Message could not be sent. Mailer Error: " . $e->getMessage();
    echo $errorMessage;
    afficheMessageConsole("L'email n'a pas pu être envoyé. Erreur : " . $e->getMessage());
}
  
}

