<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\DotEnv\DotEnv;

//Load Composer's autoloader
require 'vendor/autoload.php';
(new DotEnv(__DIR__ . '/.env'))->load();

if (isset($_POST['list_email'])) {
    $data_email = explode("\n",$_POST['list_email']);
    foreach($data_email as $each){
    $mail = new PHPMailer(true);
    try {

    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = getenv('SMTP_USERNAME');                     //SMTP username
    $mail->Password   = getenv('SMTP_PASSWORD');                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = getenv('SMTP_PORT');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom(getenv('SMTP_USERNAME'), getenv('SMTP_NAME'));
    
    $mail->addAddress(trim($each));               //Name is optional

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    //Attachments
    $raw_file = glob(__DIR__.'/file/*');
    $gitignore = glob(__DIR__.'/file/*.gitignore');
    $file = array_diff($raw_file, $gitignore);
    foreach($file as $each)
    {
        $mail->addAttachment($each);         //Add attachments
    }

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $_POST['subject'];
        $mail->Body    = $_POST['editordata'];
        // $mail->AltBody = 'Saya Sih Mau Nyoba';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }
    header('Location: /');
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

    <!-- include libraries(jQuery, bootstrap) -->
    <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- include summernote css/js-->
    <!-- Summernote CSS - CDN Link -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- //Summernote CSS - CDN Link -->

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container">
        <div class="col-md-12">
            <div class="text-center h1">Mailer</div>
            <form method="post">
                <div class="form-group mb-2">
                    <label for="list_email" class="text-bold">List Email</label>
                    <textarea class="form-control" name="list_email" id="list_email" cols="30" rows="10"></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="subject" class="text-bold">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject"></input>
                </div>
                <div class="form-group mb-2">
                    <label for="summernote" class="text-bold">Content</label>
                    <textarea id="summernote" name="editordata"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Kirim</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Hello Bootstrap 5',
                tabsize: 2,
                height: 100,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                    ]
            });
        });
    </script>
  </body>
</html>