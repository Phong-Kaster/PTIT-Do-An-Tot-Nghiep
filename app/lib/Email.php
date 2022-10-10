<?php 
/**
 * Email class to send advanced HTML emails
 * 
 * @author Onelab <hello@onelab.co>
 */
class Email extends PHPMailer{
    /**
     * Email template html
     * @var string
     */
    public static $template;


    /**
     * Email and notification settings from database
     * @var DataEntry
     */
    public static $emailSettings;


    /**
     * Site settings
     * @var DataEntry
     */
    public static $siteSettings;


    public function __construct(){  
        parent::__construct();

        // Get settings
        $emailSettings = self::getEmailSettings();
        
        // Get site name
        $siteSettings = self::getSiteSettings();

        $this->CharSet = "UTF-8";
        $this->isHTML();

        if ($emailSettings->get("data.smtp.host")) {
            $this->isSMTP();

            if ($emailSettings->get("data.smtp.from")) {
                $this->From = $emailSettings->get("data.smtp.from");
                $this->FromName = htmlchars($siteSettings->get("data.site_name"));
            }
            
            $this->Host = $emailSettings->get("data.smtp.host");
            $this->Port = $emailSettings->get("data.smtp.port");
            $this->SMTPSecure = $emailSettings->get("data.smtp.encryption");

            if ($emailSettings->get("data.smtp.auth")) {
                $this->SMTPAuth = true;
                $this->Username = $emailSettings->get("data.smtp.username");

                try {
                    $password = \Defuse\Crypto\Crypto::decrypt($emailSettings->get("data.smtp.password"), 
                                \Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY));
                } catch (Exception $e) {
                    $password = $emailSettings->get("data.smtp.password");
                }
                $this->Password = $password;
            }


            // If your mail server is on GoDaddy
            // Probably you should uncomment following 7 lines

            // $this->SMTPOptions = array(
            //     'ssl' => array(
            //         'verify_peer' => false,
            //         'verify_peer_name' => false,
            //         'allow_self_signed' => true
            //     )
            // );
        }
    }


    /**
     * Send email with $content
     * @param  string $content Email content
     * @return boolen          Sending result
     */
    public function sendmail($content){
        $html = self::getTemplate();
        $html = str_replace("{{email_content}}", $content, $html);

        $this->Body = $html;

        return $this->send();
    }


    /**
     * Get email settings
     * @return string|null 
     */
    private static function getEmailSettings()
    {
        if (is_null(self::$emailSettings)) {
            self::$emailSettings = \Controller::model("GeneralData", "email-settings");
        }

        return self::$emailSettings;
    }

    /**
     * Get site settings
     * @return string|null
     */
    private static function getSiteSettings()
    {
        if (is_null(self::$siteSettings)) {
            self::$siteSettings = \Controller::model("GeneralData", "settings");
        }

        return self::$siteSettings;
    }


    /**
     * Get template HTML
     * @return string 
     */
    private static function getTemplate()
    {   
        if (!self::$template) {
            $html = file_get_contents(APPPATH."/inc/email-template.inc.php");
            $Settings = self::getSiteSettings();
            
            $html = str_replace(
                [
                    "{{site_name}}",
                    "{{foot_note}}",
                    "{{appurl}}",
                    "{{copyright}}"
                ], 
                [
                    htmlchars($Settings->get("data.site_name")),
                    __("Thanks for using %s.", htmlchars($Settings->get("data.site_name"))),
                    APPURL,
                    __("All rights reserved.")
                ], 
                $html
            );
            
            self::$template = $html;
        }

        return self::$template;
    }




    /**
     * Send notifications
     * @param  string $type notification type
     * @return [type]       
     */
    public static function sendNotification($type = "new-user", $data = [])
    {
        switch ($type) {
            case "new-user":
                return self::sendNewUserNotification($data);
                break;

            case "new-payment":
                return self::sendNewPaymentNotification($data);
                break;

            case "password-recovery":
                return self::sendPasswordRecoveryEmail($data);
                break;
            
            default:
                break;
        }
    }


    /**
     * Send notification email to admins about new users
     * @return bool
     */
    private static function sendNewUserNotification($data = [])
    {
        $emailSettings = self::getEmailSettings();
        $siteSettings = self::getSiteSettings();

        if (!$emailSettings->get("data.notifications.emails") ||
            !$emailSettings->get("data.notifications.new_user")) 
        {    
            return false;
        }

        $mail = new Email;
        $mail->Subject = "New Registration";

        $tos = explode(",", $emailSettings->get("data.notifications.emails"));
        foreach ($tos as $to) {
            $mail->addAddress(trim($to));
        }

        $user = $data["user"];
        $emailbody = "<p>Hello, </p>"
                   . "<p>Someone signed up in <a href='".APPURL."'>".htmlchars($siteSettings->get("data.site_name"))."</a> with following data:</p>"
                   . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>"
                   . "<div><strong>Firstname:</strong> ".htmlchars($user->get("firstname"))."</div>"
                   . "<div><strong>Lastname:</strong> ".htmlchars($user->get("lastname"))."</div>"
                   . "<div><strong>Email:</strong> ".htmlchars($user->get("email"))."</div>"
                   . "<div><strong>Timezone:</strong> ".htmlchars($user->get("preferences.timezone"))."</div>"
                   . "</div>";

        return $mail->sendmail($emailbody);
    }


    /**
     * Send notification email to admins about new payments
     * @return bool
     */
    private static function sendNewPaymentNotification($data = [])
    {
        $emailSettings = self::getEmailSettings();
        $siteSettings = self::getSiteSettings();

        if (!$emailSettings->get("data.notifications.emails") ||
            !$emailSettings->get("data.notifications.new_user")) 
        {    
            return false;
        }

        $mail = new Email;
        $mail->Subject = "New Payment";

        $tos = explode(",", $emailSettings->get("data.notifications.emails"));
        foreach ($tos as $to) {
            $mail->addAddress(trim($to));
        }

        $order = $data["order"];
        $user = \Controller::model("User", $order->get("user_id"));

        $emailbody = "<p>Hello, </p>"
                   . "<p>New payment recevied in <a href='".APPURL."'>".htmlchars($siteSettings->get("data.site_name"))."</a> with following data:</p>"
                   . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>"
                   . "<div><strong>Payment Reason:</strong> Package (account) renew</div>"
                   . "<div><strong>User:</strong> ".htmlchars($user->get("firstname")." ".$user->get("lastname"))."&lt;".htmlchars($user->get("email"))."&gt;</div>"
                   . "<div><strong>Order ID:</strong> ".$order->get("id")."</div>"
                   . "<div><strong>Package:</strong> ".htmlchars($order->get("data.package.title"))."</div>"
                   . "<div><strong>Plan:</strong> ".ucfirst($order->get("data.plan"))."</div>"
                   . "<div><strong>Payment Gateway:</strong> ".ucfirst($order->get("payment_gateway"))."</div>"
                   . "<div><strong>Payment ID:</strong> ".htmlchars($order->get("payment_id"))."</div>"
                   . "<div><strong>Amount:</strong> ".$order->get("paid")." ".$order->get("currency")."</div>"
                   . "</div>";

        return $mail->sendmail($emailbody);
    }



    /**
     * Send recovery instructions to the user
     * @return bool
     */
    private static function sendPasswordRecoveryEmail($data = [])
    {
        $siteSettings = self::getSiteSettings();

        $mail = new Email;
        $mail->Subject = __("Password Recovery");
        $user = $data["user"];

        $hash = sha1(uniqid(readableRandomString(10), true));
        $user->set("data.recoveryhash", $hash)->save();

        $mail->addAddress($user->get("email"));

        $emailbody = "<p>".__("Hi %s", htmlchars($user->get("firstname"))).", </p>"
                   . "<p>".__("Someone requested password reset instructions for your account on %s. If this was you, click the button below to set new password for your account. Otherwise you can forget about this email. Your account is still safe.", "<a href='".APPURL."'>".htmlchars($siteSettings->get("data.site_name"))."</a>")."</p>"
                   . "<div style='margin-top: 30px; font-size: 14px; color: #9b9b9b'>"
                   . "<a style='display: inline-block; background-color: #3b7cff; color: #fff; font-size: 14px; line-height: 24px; text-decoration: none; padding: 6px 12px; border-radius: 4px;' href='".APPURL."/recovery/".$user->get("id").".".$hash."'>".__("Reset Password")."</a>"
                   . "</div>";

        return $mail->sendmail($emailbody);
    }
}