<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $recipient = $_POST['recipient'];
  $headerText = $_POST['special-text'];
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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Roboto:ital,wght@0,400;0,500;1,400;1,500&display=swap');

        @media (prefers-color-scheme: dark) {
          body {
            background-color: #F8F8F8;
            color: #000000;
          }
        }

        body {
          background-color: #F8F8F8;
          font-family: 'Roboto', sans-serif;
        }
        .container {
          max-width: 600px;
          margin: 0 auto;
        }
        .detail {
          display: block;
          margin-left: auto;
          margin-right: auto;
          height: 4px;
          width: 20%;
          border-radius: 4px;
          background-color: #1A73E8;
        }
        .top-section {
          margin: 40px 16px;
        }
        .headline {
          margin: 16px;
          padding: 32px;
          border-radius: 16px;
          background-color: #FFFFFF;
          border: 2px solid rgba(0, 0, 0, 0.05);
          box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;
        }
        .img {
          display: block;
          max-width: 100%;
          margin: 32px 0 40px;
        }
        .paragraph {
          font-family: 'Roboto', sans-serif;
          font-weight: 400;
          line-height: 1.5;
        }
        .footer-paragraph {
          font-family: 'Roboto', sans-serif;
          font-size: 0.75rem;
          font-weight: 400;
          text-align: center;
          line-height: 1.5;
          margin: 48px 0;
        }
        .subtitle {
          font-family: 'Roboto', sans-serif;
          font-weight: 500;
        }
        .sub-headline {
          margin-bottom: 48px;
        }
        label {
          display: block;
          font-family: 'Poppins', serif;
          font-weight: 500;
        }
        input, textarea {
          display: block;
          margin-top: 8px;
          width: calc(100% - 20px);
          padding: 8px;
          border-radius: 4px;
          border: none;
          box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
          font-family: 'Roboto', sans-serif;
          font-weight: 400;
        }
        .btn-primary {
          width: 50%;
          display: block;
          padding: 8px;
          margin: 32px auto 0;
          background-color: #1A73E8;
          border: none;
          border-radius: 4px;
          box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
          transition: 0.2s ease;
        }
        .btn-primary:hover {
          background-color: #155BB2;
        }
        .btn-send-email {
          display: block;
          padding: 4px;
          margin-left: auto;
          margin-right: auto;
          background-color: #FBBC04;
          border: none;
          border-radius: 4px;
          box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
          transition: 0.2s ease;
        }
        .btn-send-email:hover {
          background-color:#E0A903;
        }
        .pointer {
          cursor: pointer;
        }
        .text-center {
          text-align: center;
        }
        .poppins {
          font-family: 'Poppins', serif;
        }
        .roboto {
          font-family: 'Roboto', sans-serif;
        }
        .normal {
          font-weight: 400;
        }
        .semibold {
          font-weight: 500;
        }
        .bold {
          font-weight: 600;
        }
        .italic {
          font-style: italic;
        }
        .upper {
          text-transform: uppercase;
        }
        .text-yellow {
          color: #FBBC04;
        }
        .text-blue {
          color: #1A73E8;
        }
        .text-red {
          color: #EA4335;
        }
        .text-white {
          color: #FFFFFF;
        }
      </style>
    </head>
    <body>
      <div class='container'>
        <header>
          <h1 class='text-center poppins bold upper'>To Current</h1>
          <span class='detail'></span>
        </header>
        <section class='top-section'>
          <h2 class='subtitle'>Bom dia! 👋</h2>
          <q class='paragraph italic'>$headerText</q>
        </section>
        <main>";

  $attachments = [];

  foreach ($headlineTitles as $index => $title) {
    $subtitle = $headlineSubtitles[$index];
    $paragraph = $headlineParagraphs[$index];
    $imageTag = '';

    if ($headlineImages && $headlineImages['error'][$index] == UPLOAD_ERR_OK) {
      $tmpName = $headlineImages['tmp_name'][$index];
      $fileName = basename($headlineImages['name'][$index]);
      $filePath = "../public/img/" . $fileName;

      // Save the image file
      if (move_uploaded_file($tmpName, $filePath)) {
        $attachments[] = $filePath;
        $imageTag = "<img class='img' src='cid:" . md5($filePath) . "'>";
      }
    }

    $emailContent .= "
        <div class='headline'>
          <span class='poppins bold upper text-yellow'>Tipo</span>
          <div class='sub-headline'>
            <h1 class='roboto semibold'>$title</h1>
            $imageTag
            <h3 class='subtitle'>$subtitle</h3>
            <p class='paragraph'>$paragraph</p>
          </div>
        </div>";
  }

  $emailContent .= "
        </main>
      </div>
      <footer>
        <p class='footer-paragraph'>To Current © Direitos reservados.</p>
      </footer>
    </body>
    </html>";

  $mail = new PHPMailer(true);
  try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'newsletter.to.current@gmail.com';
    $mail->Password = 'kwiv emmm kman uvnm';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('newsletter.to.current@gmail.com', 'To Current');
    $mail->addAddress($recipient);

    // Attachments
    foreach ($attachments as $attachment) {
      $mail->addAttachment($attachment, basename($attachment), 'base64', 'image/jpeg');
    }

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'To Current';
    $mail->Body = $emailContent;

    // Embed images
    foreach ($attachments as $attachment) {
      $mail->addEmbeddedImage($attachment, md5($attachment));
    }

    $mail->send();
    echo "Email enviado com sucesso!";
  } catch (Exception $e) {
    echo "Erro ao enviar o email: {$mail->ErrorInfo}";
  }

  // Remove the uploaded images after sending email
  foreach ($attachments as $attachment) {
    if (file_exists($attachment)) {
      unlink($attachment);
    }
  }
}
