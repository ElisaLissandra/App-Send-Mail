<?php
  require "./bibliotecas/PHPMailer/Exception.php";
  require "./bibliotecas/PHPMailer/OAuth.php";
  require "./bibliotecas/PHPMailer/PHPMailer.php";
  require "./bibliotecas/PHPMailer/POP3.php";
  require "./bibliotecas/PHPMailer/SMTP.php";

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;


  //print_r($_POST);

  class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;

    public function __get($atributo) {
      return $this->$atributo;
    }

    public function __set($atributo, $valor) {
      $this->$atributo = $valor;
    }

    public function mensagemValida() {
      if(empty($this->para) || empty($this->assunto)|| empty($this->mensagem)){
        return false;
      }

      return true;

    }
  }

  $mensagem = new Mensagem();
  $mensagem->__set('para', $_POST['para']);
  $mensagem->__set('assunto', $_POST['assunto']);
  $mensagem->__set('mensagem', $_POST['mensagem']);

  //print_r($mensagem);

  if(!$mensagem->mensagemValida()){
    echo 'Mensagem não é válida';
    die();
  } 

  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'testecursos21@gmail.com';                     //SMTP username
    $mail->Password   = 'curso2107';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('testecursos21@gmail.com', 'Teste Curso Remetente');
    $mail->addAddress($mensagem->__get('para'));     //Add a recipient
    $mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessário utilizar um client que suporte HTLMvpara ter acesso total ao conteúdo dessa mensagem';

    $mail->send();
      echo 'Mensagem enviada com sucesso';
  } catch (Exception $e) {
    echo 'Não foi possível enviar este e-mail! Por favor novamente mais tarde.';
    echo "Detalhes do erro: {$mail->ErrorInfo}";
  }

?>