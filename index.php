<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compose Email</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Roboto:ital,wght@0,400;0,500;1,400;1,500&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <form action="email/send_email.php" method="post" enctype="multipart/form-data">
      <div class="top-section">
        <label class="poppins semibold" for="recipient">Recipient Email:</label>
        <input type="email" id="recipient" name="recipient" required><br>
        <label class="poppins semibold" for="special-text">Header Text</label>
        <input type="text" id="special-text" name="special-text" required><br>
      </div>

      <div id="headlines-container"></div>

      <button type="button" class="btn-primary poppins bold text-white pointer" onclick="addHeadline()">Add Headline</button><br>
      <button type="submit" class="btn-send-email poppins bold text-white pointer">Send Email</button>
    </form>
  </div>
</body>

<script src="public/js/form.js"></script>

</html>