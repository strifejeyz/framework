<?php  namespace Kernel;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    /**
     * Holds PHPMailer's instance initiated
     * in __constructor()
     */
    private $instance;


    /**
     * Returns PHPMailer's instance
     */
    public function __construct()
    {
        $this->instance             = new PHPMailer(true);
        $this->instance->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->instance->Username   = SMTP_USERNAME;
        $this->instance->Password   = SMTP_PASSWORD;
        $this->instance->Host       = SMTP_HOST;
        $this->instance->Port       = SMTP_PORT;
        $this->instance->SMTPAuth   = true;
        $this->instance->isHTML(SMTP_USE_HTML);
        $this->instance->isSMTP();
    }


    /**
     * For whenever class is called like a function
     * e.g. $mail = new Mail; $mail()
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->instance;
    }


    /**
     * Returns PHPMailer's instance
     */
    public function instance()
    {
        return $this->instance;
    }


    /**
     * Set the email subject
     */
    public function subject($subject)
    {
        $this->instance->Subject = $subject;
        return $this;
    }


    public function to() {
        $recipients = func_get_args();
        foreach ($recipients as $recipient) {
            if (is_array($recipient)) {
                $this->instance->addAddress(array_keys($recipient)[0], array_values($recipient)[0]);
            } else {
                $this->instance->addAddress($recipient);
            }
        }
        return $this;
    }

    public function cc() {
        $CCs = func_get_args();
        foreach ($CCs as $cc) {
            if (is_array($cc)) {
                $this->instance->addCC(array_keys($cc)[0], array_values($cc)[0]);
            } else {
                $this->instance->addCC($cc);
            }
        }
        return $this;
    }

    public function bcc() {
        $BCCs = func_get_args();
        foreach ($BCCs as $bcc) {
            if (is_array($bcc)) {
                $this->instance->addBCC(array_keys($bcc)[0], array_values($bcc)[0]);
            } else {
                $this->instance->addBCC($bcc);
            }
        }
        return $this;
    }


    public function from($email, $fromName = null) {
        if (is_null($fromName)) {
            $this->instance->setFrom($email);
        } else {
            $this->instance->setFrom($email, $fromName);
        }
        return $this;
    }


    public function body($contents) {
        $this->instance->Body = $contents;

        return $this;
    }



    public function send() {
        return $this->instance()->send();
    }

    public function attachment($file_path) {
        $this->instance->addAttachment($file_path);
        return $this;
    }
}