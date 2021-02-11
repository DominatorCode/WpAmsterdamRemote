<?php

namespace booking;

use DirectoryCustomFields\ConfigurationParameters;

require_once 'acf-local-fields/ConfigurationParameters.php';

/**
 * Sends submitted form data from client to admin email
 */
class BookingForm
{
    public $email_person;
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
        $this->email_person = $pMail;
        $this->phone = $pPhone;
    }
    
    
    public function sendMail() : string
    {
        // check if data contains some callback info
        $result = $this->checkInput();
    
        if (empty(ConfigurationParameters::$email_booking)) {
            ConfigurationParameters::$email_booking = get_theme_mod('email-admin');
        }
        
        // message text
        if ($result === 'OK') {
            $message_mail = '<html><body>';
            $message_mail .= '<h1>Client data</h1>';
            $message_mail .= '<table style="border-color: #666;" >';
            $message_mail .= "<tr style='background: #eee;'>" .
                "<td><strong>Name:</strong> </td><td>" . $this->name_person . "</td></tr>";
            $message_mail .= "<tr><td><strong>Email:</strong> </td><td>" . $this->email_person . "</td></tr>";
            $message_mail .= "<tr><td><strong>Phone:</strong> </td><td>" .
                $this->phone . "</td></tr>";
            $message_mail .= "<tr><td><strong>Location:</strong> </td><td>" . $this->location . "</td></tr>";
            $message_mail .= "<tr><td><strong>Client message:</strong> </td><td>" . $this->message . "</td></tr>";
            $message_mail .= "</table>";
            $message_mail .= "<br><h1>Model info</h1>";
            $message_mail .= '<table style="border-color: #666;" >';
            
            $message_mail .= "<tr><td><strong>Model name:</strong> </td><td>" .
                $this->name_model . "</td></tr>";
            
            $message_mail .= "<tr><td><strong>Date:</strong> </td><td>" .
                $this->date . "</td></tr>";
            
            $message_mail .= "<tr><td><strong>Duration:</strong> </td><td>" .
                $this->duration . "</td></tr>";
            
            $message_mail .= "<tr><td><strong>Callback:</strong> </td><td>" .
                $this->contact_type . "</td></tr>";
            $message_mail .= "</table>";
            $message_mail .= "</body></html>";
            
            // mail headers
            $headers = array('Content-Type: text/html; charset=UTF-8',
                'Reply-To: ' . $this->name_person . ' <' . $this->email_person . '>',
                "From: " . get_bloginfo('name') );
            
            if (wp_mail(
                ConfigurationParameters::$email_booking,
                ConfigurationParameters::$name_booking_subject,
                $message_mail,
                $headers
            )) {
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
    private function checkInput() : string
    {
        $error = array();
        if (!preg_match('~^\([d]{3}\)[- ][d]{3}-[d]{4}$~', $this->phone)) {
            $error[] = __('Phone', 'cuf-lang');
        }
        if (!is_email($this->email_person)) {
            $this->email_person = "";
            $error[] = __('Email', 'cuf-lang');
        }
        
        if (empty($this->name_person)) {
            $this->name_person = 'Anonymous';
        }
        
        if (count($error) > 1) {
            return __('Check these fields:', 'cuf-lang') . ' ' . implode(', ', $error);
        }
        
        return 'OK';
    }
}
