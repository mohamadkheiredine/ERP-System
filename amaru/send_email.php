<?php
// Basic security check
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: form.html");
    exit;
}

// Collect form data safely
$name  = htmlspecialchars(trim($_POST['fullname']));
$email = htmlspecialchars(trim($_POST['email']));
$phone = htmlspecialchars(trim($_POST['phone']));

// Receiver email (your inbox)
$to = "info@amarudubai.com";

// Email subject & body
$subject = "New Amaru Contact Form Submission";
$message = "
You have received a new submission from the Amaru form:\n\n
Full Name: $name\n
Email: $email\n
Phone Number: $phone\n
-----------------------------\n
Sent on: " . date('Y-m-d H:i:s') . "\n
";

// Email headers
$headers  = "From: no-reply@amarudubai.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($to, $subject, $message, $headers)) {
    // Success - show thank you message
    echo "
    <html>
      <head>
        <meta http-equiv='refresh' content='4; url=form.html'>
        <title>Message Sent</title>
        <style>
          body {
            background-color: #0a0a0a;
            color: #e8e3d5;
            font-family: 'Open Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
          }
          h1 { color: #bfa36a; }
        </style>
      </head>
      <body>
        <div>
          <h1>Thank you, $name!</h1>
          <p>Your information has been sent successfully.<br>We'll be in touch soon.</p>
          <p>Redirecting back...</p>
        </div>
      </body>
    </html>";
} else {
    echo "
    <html><body style='background:#0a0a0a;color:#ff9999;text-align:center;padding-top:100px;'>
      <h1>❌ Something went wrong.</h1>
      <p>Please try again later.</p>
      <a href='form.html' style='color:#bfa36a;'>← Go Back</a>
    </body></html>";
}
?>