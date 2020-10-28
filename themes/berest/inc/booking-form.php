<?php

use DirectoryCustomFields\ConfigurationParameters;

require_once 'acf-local-fields/ConfigurationParameters.php';

/**
 * Class BookingForm
 */
class BookingForm
{

	public $email_from;
	public $phone;
	public $name_person;
	public $location;
	public $message;

	public $name_model;
	public $date;

	public $duration;
	public $contact_type;

	/**
	 * BookingForm constructor.
	 * @param $pMail
	 * @param $pPhone
	 */
	public function __construct($pMail, $pPhone)
	{
		$this->email_from = $pMail;
		$this->phone = $pPhone;
	}


	public function sendMail(): string
	{
		$result = $this->checkInput();
		$result = 'OK';
		if ($result === 'OK') {
			$result = '';

			$to = ConfigurationParameters::$email_booking;

			$headers =
				"MIME-Version: 1.0\r\n" .
				"Reply-To: \"$this->name_person\" <$this->email_from\r\n" .
				"Content-Type: text/plain; charset=\"" . get_settings('blog_charset') . "\"\r\n";
			if (!empty($this->email_from)) {
				$headers .= "From: " . get_bloginfo('name') . " - $this->name_person <$this->email_from\r\n";
			} else if (!empty($this->phone)) {
				$headers .= "From: " . get_bloginfo('name') . " - $this->name_person <$this->phone\r\n";
			}

			$full_msg =
				"Name: $this->name_person\r\n" .
				"Email: $this->email_from\r\n" .
				'Subject: ' . ConfigurationParameters::$name_booking_subject . "\r\n\r\n" .
				wordwrap($this->message, 76, "\r\n") . "\r\n\r\n" .
				'Referer: ' . $_SERVER['HTTP_REFERER'] . "\r\n" .
				'Browser: ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n";

			if (wp_mail($to, ConfigurationParameters::$name_booking_subject, $full_msg, array('Content-Type: text/html; charset=UTF-8'))) {
				$result = 'msg_ok';
			} else {
				$result = 'msg_err';
			}
		}
		return $result;
	}

	/**
	 * Checks form data
	 * @return string
	 */
	private function checkInput(): string
	{

		$error = array();
		if (empty($this->phone)) {
			$error[] = __('Phone', 'cuf-lang');
		}
		if (!is_email($this->email_from)) {
			$error[] = __('Email', 'cuf-lang');
		}

		if (!empty($error)) {
			return __('Check these fields:', 'cuf-lang') . ' ' . implode(', ', $error);
		}

		return 'OK';
	}
}