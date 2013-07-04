<?php
set_include_path(get_include_path() . ':' . '.');

require_once('MailGun/Email.php');
use \MailGun\Email;

//Instantiate with your domain and key (no, that's not my real key)
$email = new Email('dswebhost.net', 'key-p9q8bfwnelqn3iuqbydf9sapn23lais8');

//Populate the object
$response = $email->setFrom('no-reply@dswebhost.net', 'Darkstar')
    ->setReplyTo('darkstar@dswebhost.net')
    ->addTo('example@example.com')
    ->setSubject('Ignore this')
    ->setText('Sending a test email')
    ->addTag('test emails')
    ->setTestMode(true)
    ->send();

if ($response->getHttpCode() !== 200) {
    throw new \Exception('Mail not sent');
} else {
    echo 'Email sent successfully.  Email ID: ' . $response->getResponseObject()->id;
}