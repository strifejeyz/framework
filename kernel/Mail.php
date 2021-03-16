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
}