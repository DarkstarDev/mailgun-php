# Introduction to mailgun-php

## Purpose

I created this set of classes to ease sending email through MailGun. It took me a little while to send my first
successful email with MailGun between reading their docs fixing any errors I had.  I wanted to write something that
would make getting up and running with MailGun take only as long as writing the email itself.
All the fields listed in the [Messages API doc](http://documentation.mailgun.com/api-sending.html) are supported.

## Requirements

There are very few requirements to use this library.

* PHP (>= 5.3)
* libcurl
* A MailGun account

## Setup

Copy the MailGun directory to any directory in your include path or add MailGun's parent directory to the include path.

## Use

Using the library is very simple.  First, make sure you've got the current directory in the include path:

    set_include_path(get_include_path() . ':' . '.');

Of course, you won't have to do this if you've copied the MailGun directory to any directory in your include path.  Next
we instantiate `\MailGun\Email` with your domain and your API key:

    $email = new Email('dswebhost.net', 'key-p9q8bfwnelqn3iuqbydf9sapn23lais8');

Don't worry, that's not my key, but yours should look a lot like that.  The only thing left to do is set the properties
of our email and send it, which can be done in one fell swoop.  Since we're just testing to make sure we can send our
first email we'll use `setTestMode(true)`.  You can use `setTestMode(false)` or simply omit `setTestMode()` altogether
when you've finished testing and are ready to send out real email.

    $response = $email->setFrom('no-reply@dswebhost.com', 'Darkstar')
        ->addTo('example@example.com')
        ->setSubject('Ignore this')
        ->setText('Sending a test email')
        ->setTestMode(true)
        ->send();


**NOTE:** Only to, from, subject, and either text or html are required fields.

Any fields that accept only one value are prefixed with *set*.  Any fields that accept more than one value are prefixed
with *add*

Finally, we can do some post-processing to ensure our request was acknowledged by MailGun:

    if ($response->getHttpCode() !== 200) {
        throw new \Exception('Mail not sent');
    } else {
        echo 'Email sent successfully.  Email ID: ' . $response->getResponseObject()->id;
    }

`\MailGun\HttpResponse` contains all the information about the cURL request, including the server's response.  You can
get the response as it came back from the server (a string) using `getResponse()` or get the `json_decode`'d object with
`getResponseObject()`.