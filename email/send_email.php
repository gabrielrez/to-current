<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $recipient = $_POST['recipient'];
  $headlineTitles = isset($_POST['headline_titles']) ? $_POST['headline_titles'] : [];
  $headlineSubtitles = isset($_POST['headline_subtitles']) ? $_POST['headline_subtitles'] : [];
  $headlineParagraphs = isset($_POST['headline_paragraphs']) ? $_POST['headline_paragraphs'] : [];
  $headlineImages = isset($_FILES['headline_images']) ? $_FILES['headline_images'] : null;

  $emailContent = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Newsletter</title>
      <link href='https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Roboto:ital,wght@0,400;0,500;1,400;1,500&display=swap' rel='stylesheet'>
      <style>
        body { font-family: 'Poppins', sans-serif; }
        .headline { margin-bottom: 20px; }
        .headline img { max-width: 100%; height: auto; }
      </style>
    </head>
    <body>
    ";

  foreach ($headlineTitles as $index => $title) {
    $subtitle = $headlineSubtitles[$index];
    $paragraph = $headlineParagraphs[$index];
    $image = '';

    if ($headlineImages && $headlineImages['error'][$index] == UPLOAD_ERR_OK) {
      $tmpName = $headlineImages['tmp_name'][$index];
      $imageData = base64_encode(file_get_contents($tmpName));
      $imageType = $headlineImages['type'][$index];
      $image = "<img src='data:$imageType;base64,$imageData'>";
    }

    $emailContent .= "
        <div class='headline'>
          <h1>$title</h1>
          <h2>$subtitle</h2>
          <p>$paragraph</p>
          $image
        </div>
        ";
  }

  $emailContent .= "
    </body>
    </html>
    ";

  $mail = new PHPMailer(true);
  try {
    // Configurações do servidor
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configurações do email
    $mail->setFrom('from@example.com', 'Your Name');
    $mail->addAddress($recipient);
    $mail->isHTML(true);
    $mail->Subject = 'Sua Newsletter Atualizada';
    $mail->Body = $emailContent;

    $mail->send();
    echo "Email enviado com sucesso!";
  } catch (Exception $e) {
    echo "Erro ao enviar o email: {$mail->ErrorInfo}";
  }
}
