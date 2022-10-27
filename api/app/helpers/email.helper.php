<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once "vendor/autoload.php";

    class MyEmail
    {
        private static $html;
        private static $mail;

        public function __construct()
        {
            parent::__construct();
            $this->setup();
        }

        private static function setupMail()
        {
            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            //SMTP::DEBUG_OFF = off (for production use)
            //SMTP::DEBUG_CLIENT = client messages
            //SMTP::DEBUG_SERVER = client and server messages
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            //Set the hostname of the mail server
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465; 
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = 'phongkaster@gmail.com';
            //Password to use for SMTP authentication
            $mail->Password = 'pmyicteoyfutbgzz';
            //Set who the message is to be sent from
            $mail->setFrom('phongkaster@gmail.com', TITLE);
            //Set an alternative reply-to address
            $mail->addReplyTo('phongkaster@gmail.com', TITLE);
            $mail->isHTML();

            $mail->CharSet = "UTF-8";
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            // $mail->addAttachment('images/phpmailer_mini.png');

            return $mail;
        }

        /**
         * @author Phong-Kaster
         * @since 11-10-2022
         * set up HTML body
         * TITLE is a constant in app/config/config.php at line 42
         * APPURL is a constant in index.php at line 52
         */
        private static function setupHTMLBody()
        {
            $logo = "https://images.fineartamerica.com/images/artworkimages/mediumlarge/3/umbrella-corporation-black-donnell-rose.jpg";
            $html = file_get_contents(APPPATH."/inc/email-template.inc.php");


            $html = str_replace("{{site_name}}","<img src='".$logo."' width='550px' alt='Umbrella Corporation'>", $html);
            $html = str_replace("{{appurl}}",VIDEO, $html);
            $html = str_replace("{{title}}",TITLE, $html);
            $html = str_replace("{{copyright}}",COPYRIGHT, $html);
            
            
            return $html;
        }

        /**
         * @author Phong-Kaster
         * @since 11-10-2022
         * send email with new registration
         */
        public static function signup($data = [])
        {
            /**Step 1 - get input data */
            $email = $data["email"];
            $phone = $data["phone"];
            $name = $data["name"];
            $password = $data["password"];


            /**Step 2 - setup Mail & HTML Body */
            $mail = self::setupMail();
            $mail->addAddress($email, $name);
            $mail->Subject = "Chào mừng đến với Umbrella Corporation";
            $htmlBody = self::setupHTMLBody();


            /**Step 3 - prepare content */
            $app_url = str_replace("/api", "", APPURL);
            $body = "<p>Xin chào, <strong>".htmlspecialchars($name)."</strong> </p>"
                . "Chào mừng bạn đã đăng kí trở thành thành viên của Umbrella Corporation"
                . "<p>Thông tin đăng kí tài khoản của bạn tại <a href='".$app_url."'>".htmlspecialchars(TITLE)."</a> như sau:</p>"
                . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>"
                . "<div><strong>Name:</strong> ".htmlspecialchars($name)."</div>"
                . "<div><strong>Phone:</strong> ".htmlspecialchars($phone)."</div>"
                . "<div><strong>Email:</strong> ".htmlspecialchars($email)."</div>"
                . "<div><strong>Password:</strong> ".htmlspecialchars($password)."</div>"
                . "</div>";
            $htmlBody = str_replace("{{email_content}}",$body, $htmlBody);
            $htmlBody = str_replace("{{foot_note}}", "Nếu bạn không đăng ký tài khoản này. Vui lòng bỏ qua email này !", $htmlBody);
            $mail->Body = $htmlBody;
            

            /**Step 4 - send email */
            // print_r($htmlBody);
            try
            {
                if (!$mail->send()) 
                {
                    print_r("Signup Email Error: ".$mail->ErrorInfo);
                }
                else 
                {
                    //do nothing
                }
            }
            catch(Exception $ex)
            {
                print_r("Signup Email: ".$ex->getMessage);
            }
        }

        /**
         * @author Phong-Kaster
         * @since 11-10-2022
         * send email to recovery password
         */
        public static function recoveryPassword($data = [])
        {
            /**Step 1 - get input data */
            $Doctor = $data["doctor"];
            $email = $Doctor->get("email");
            $name = $Doctor->get("name");
            $id = $Doctor->get("id");

            /**Step 2 - always create a 15-digit number to be recovery token */
            $recovery_token = generateRandomString();
            $Doctor->set("recovery_token", $recovery_token)
                ->save();

            /**Step 3 - setup email & html content */
            $mail = self::setupMail();
            $mail->addAddress($email, $name);
            $mail->Subject = "Khôi phục mật khẩu tài khoản tại Umbrella Corporation";
            $htmlBody = self::setupHTMLBody();

            $body = "<p>Chào <strong>".htmlspecialchars($name)."</strong>, </p>"
                   . "<p>Ai đó đang tạo yêu cầu hướng dẫn đặt lại mật khẩu tài khoản của bạn tại <strong><a href='".APPURL."'>".htmlchars(TITLE)."</a></strong>. 
                    Nếu đó là bạn, hãy nhập dãy số sau để khôi phục: <strong>".$recovery_token."</strong></p>"
                   . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>";

            $htmlBody = str_replace("{{email_content}}",$body, $htmlBody);
            $htmlBody = str_replace("{{foot_note}}", "Nếu bạn không tạo yêu cầu khôi phục mật. Vui lòng bỏ qua email này. Tài khoản của bạn vẫn an toàn !", $htmlBody);
            $mail->Body = $htmlBody;

            /**Step 4 - send email */
            // print_r($htmlBody);
            try
            {
                if (!$mail->send()) 
                {
                    print_r("Recovery Password Email Error: ".$mail->ErrorInfo);
                }
                else 
                {
                    //do nothing
                }
            }
            catch(Exception $ex)
            {
                print_r("Recovery Password Email: ".$ex->getMessage);
            }
        }
    }
?>