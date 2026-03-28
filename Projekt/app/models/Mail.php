<?php

declare(strict_types=1);
namespace App\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once LIB_DIR . "vendor/autoload.php";

class Mail {
    private PHPMailer $mailer;

    public function __construct(array $config = []) {
        $this->mailer = new PHPMailer(true);

		$this->mailer->isSMTP();
		$this->mailer->Host = $config["host"];
		$this->mailer->Port = $config["port"];
		$this->mailer->setFrom($config["from"]);
		$this->mailer->SMTPAuth = $config["auth"];
		$this->mailer->CharSet = "UTF-8";
		$this->mailer->isHTML(true);
    }

    public function send (string $to, string $title, string $body) : bool {
		$this->mailer->addAddress($to);

		$this->mailer->Subject = $title;
		$this->mailer->Body = $body;

		return $this->mailer->send();
	}
}