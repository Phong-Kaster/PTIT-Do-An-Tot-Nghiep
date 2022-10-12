<?php
/**
 * Signup Controller
 */

class SignupController extends Controller
{
    /**
     * Process
     */
    public function process()
    {
        $AuthUser = $this->getVariable("AuthUser");

        if ($AuthUser) {
            $this->resp->msg = "You logged in !";
            $this->jsonecho();
        }

        $request_method = Input::method();
        if( $request_method === 'POST')
        {
            $this->signup();
        }
        // else if( $request_method === 'GET')
        // {
        //     $this->sendEmail();
        // }
    }

    private function sendEmail()
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
        $mail->setFrom('phongkaster@gmail.com', 'Phong Kaster');
        //Set an alternative reply-to address
        $mail->addReplyTo('phongkaster@gmail.com', 'Phong Kaster');
        //Set who the message is to be sent to
        $mail->addAddress('n18dccn147@student.ptithcm.edu.vn', 'John Doe');
        //Set the subject line
        $mail->Subject = 'Registration';
        $mail->isHTML();
        $mail->CharSet = "UTF-8";

        $html = file_get_contents(APPPATH."/inc/email-template.inc.php");
        $html = str_replace("{{site_name}}","Phong-Kaster", $html);
        $html = str_replace("{{foot_note}}", "Thanks for using ". htmlspecialchars("Phong-Kaster"), $html);
        $html = str_replace("{{appurl}}",APPURL, $html);
        $html = str_replace("{{copyright}}","All rights reserved.", $html);

        $app_url = str_replace("/api", "", APPURL);
        $body = "<p>Xin chào, </p>"
                . "<p>Ai đó đã đăng kí tài khoản Bác sĩ tại <a href='".$app_url."'>".htmlspecialchars("Phong-Kaster")."</a> với thông tin sau:</p>"
                . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>"
                . "<div><strong>Name:</strong> ".htmlspecialchars("John Doe")."</div>"
                . "<div><strong>Phone:</strong> ".htmlspecialchars("0794104124")."</div>"
                . "<div><strong>Email:</strong> ".htmlspecialchars("n18dccn147@student.ptithcm.edu.vn")."</div>"
                . "<div><strong>Password:</strong> ".htmlspecialchars("123456")."</div>"
                . "</div>";
        $html = str_replace("{{email_content}}",$body, $html);
        $mail->Body = $html;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        // $mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
    }

    private function sendEmail2()
    {

    }

    /**
     * Signup
     * @return void
     */
    private function signup()
    {
        /**Step 1 - required_fields */
        $this->resp->result = 0;

        $required_fields  = [
            "email", 
            "phone", 
            "password", 
            "password-confirm", 
            "name",
            "price",

        ];
        foreach ($required_fields as $field) 
        {
            if (!Input::post($field)) 
            {
                $this->resp->msg = "Missing field: ".$field;
                $this->jsonecho();
            }
        }

        $email = Input::post("email");
        $phone = Input::post("phone");
        $password = Input::post("password");
        $passwordConfirm = Input::post("password-confirm");
        $name = Input::post("name");
        $description = Input::post("description");
        $price = Input::post("price") ? (int)Input::post("price") : 100000 ;
        $role = "member"; // default
        $active = 1;
        $avatar = Input::post("avatar") ? Input::post("avatar") : "";
        $specialityId = 1;
        $clinicId = 1;



        /**Step 2 - check input data  */
        /**Step 2.1 - FILTER_VALIDATE_EMAIL */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->resp->msg = "Email is not correct format. Try again !";
            $this->jsonecho();
        }


        /**Step 2.2 - email duplication */
        $Doctor = Controller::model("Doctor", $email);
        if( $Doctor->isAvailable() )
        {
            $this->resp->msg = "This email is used by someone. Try another !";
            $this->jsonecho();
        }


        /**Step 2.3 - password filter */
        if (mb_strlen($password) < 6) 
        {
            $this->resp->msg = __("Password must be at least 6 character length!");
            $this->jsonecho();
        } 
        else if ($password != $passwordConfirm) 
        {
            $this->resp->msg = __("Password confirmation does not equal to password !");
            $this->jsonecho();
        }


        /**Step 2.4 - name validation*/
        $name_validation = isVietnameseName($name);
        if( $name_validation == 0 ){
            $this->resp->msg = "Vietnamese name only has letters and space";
            $this->jsonecho();
        }
        

        /**Step 2.5 - phone validation */
        if( strlen($phone) < 10 ){
            $this->resp->msg = "Phone number has at least 10 number !";
            $this->jsonecho();
        }

        $phone_number_validation = isNumber($phone);
        if( !$phone_number_validation ){
            $this->resp->msg = "This is not a valid phone number. Please, try again !";
            $this->jsonecho();
        }



        /**Step 2.6 - price */
        if( $price < 100000 )
        {
            $this->resp->msg = "Price must greater than 100.000 !";
            $this->jsonecho();
        }

        
        /**Step 3 - save */
        try 
        {
            $Doctor = Controller::model("Doctor");
            $Doctor->set("email", strtolower($email))
                    ->set("phone", $phone)
                    ->set("password", password_hash($password, PASSWORD_DEFAULT))
                    ->set("name", $name)
                    ->set("description", $description)
                    ->set("price", $price)
                    ->set("role", $role)
                    ->set("active", $active)
                    ->set("avatar", $avatar)
                    ->set("create_at", date("Y-m-d H:i:s"))
                    ->set("update_at", date("Y-m-d H:i:s"))
                    ->set("speciality_id", $specialityId)
                    ->set("clinic_id", $clinicId)
                    ->save();

            $this->resp->result = 1;
            $this->resp->msg = "Doctor account is created successfully. Don't forget to check Gmail to get password !";
            $this->resp->data = array(
                "email" => $Doctor->get("email"),
                "phone" => $Doctor->get("phone"),
                "name" => $Doctor->get("name"),
                "description" => $Doctor->get("description"),
                "price" => $Doctor->get("price"),
                "role" => $Doctor->get("role"),
                "active" => $Doctor->get("active"),
                "avatar" => $Doctor->get("avatar"),
                "create_at" => $Doctor->get("create_at"),
                "update_at" => $Doctor->get("update_at"),
                "speciality_id" => $Doctor->get("speciality_id"),
                "clinic_id" => $Doctor->get("clinic_id")
            );

            $data = [
                "email" => strtolower($email),
                "phone" => $phone,
                "name" => $name,
                "password" => $password
            ];

            MyEmail::signup($data);
        } 
        catch (\Exception $ex) 
        {
            $this->resp->msg = $ex->getMessage();
        }
        $this->jsonecho();
    }
}