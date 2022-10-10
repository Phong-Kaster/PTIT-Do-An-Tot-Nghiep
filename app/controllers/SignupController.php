<?php
/**
 * Signup Controller
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";

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
        else if( $request_method === 'GET')
        {
            $this->sendEmail();
        }
    }

    private function sendEmail()
    {

        $this->resp->result = 0;
        
        //PHPMailer Object
        $mail = new PHPMailer(true); //Argument true in constructor enables exceptions

        //From email address and name
        $mail->From = "phongkaster@gmail.com";
        $mail->FromName = "Phong Kaster";

        //To address and name
        $mail->addAddress("n18dccn147@student.ptithcm.edu.vn", "N18DCCN147");

        //Address to which recipient will reply
        $mail->addReplyTo("phongkaster@gmail.com", "Reply");

        //CC and BCC
        // $mail->addCC("cc@example.com");
        // $mail->addBCC("bcc@example.com");

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = "Subject Text";
        $mail->Body = "<i>Mail body in HTML</i>";
        $mail->AltBody = "This is the plain text version of the email content";
        try 
        {
            $mail->send();
            echo "Message has been sent successfully";

            $this->resp->result = 1;
            $this->resp->msg = "Email sent !";
        } 
        catch (\Exception $ex) 
        {
            $this->resp->msg = $ex->getMessage();
        }
        $this->jsonecho();
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
        $avatar = Input::post("avatar") ? Input::post("avatar") : "";
        $specialityId = 1;
        $clinicId = 1;



        /**Step 2 - check input data  */
        /**Step 2.1 - FILTER_VALIDATE_EMAIL */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->resp->msg = "Email is not valid. Try again !";
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
            $this->resp->msg = __("Password confirmation didn't match!");
            $this->jsonecho();
        }


        /**Step 2.4 - name validation*/
        $name_validation = isVietnameseName($name);
        if( $name_validation != 1 ){
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
                    ->set("avatar", $avatar)
                    ->set("create_at", date("Y-m-d H:i:s"))
                    ->set("update_at", date("Y-m-d H:i:s"))
                    ->set("speciality_id", $specialityId)
                    ->set("clinic_id", $clinicId)
                    ->save();

            $this->resp->result = 1;
            $this->resp->msg = "Doctor account is created successfully !";
            $this->resp->data = array(
                "email" => $Doctor->get("email"),
                "phone" => $Doctor->get("phone"),
                "name" => $Doctor->get("name"),
                "description" => $Doctor->get("description"),
                "price" => $Doctor->get("price"),
                "role" => $Doctor->get("role"),
                "avatar" => $Doctor->get("avatar"),
                "create_at" => $Doctor->get("create_at"),
                "update_at" => $Doctor->get("update_at"),
                "speciality_id" => $Doctor->get("speciality_id"),
                "clinic_id" => $Doctor->get("clinic_id")
            );
        } 
        catch (\Exception $ex) 
        {
            $this->resp->msg = $ex->getMessage();
        }
        $this->jsonecho();
    }
}