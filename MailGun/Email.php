<?php
Namespace MailGun;

require_once('MailGun/Exception.php');
require_once('MailGun/HttpResponse.php');
/**
 * MailGun wrapper
 * @author Orlando Marin
 *
 * Copyright 2013 Orlando Marin <darkstar@dswebhost.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class Email {

    const API_URL = 'https://api.mailgun.net/v2/';

    protected $_domain;
    protected $_apiKey;
    protected $_data = array();

    /**
     * @param string $domain The domain of the site to send email for
     * @param string $apiKey The API key for the account
     */
    public function __construct($domain, $apiKey) {
        $this->_apiKey = $apiKey;
        $this->_domain = $domain;
    }

    /**
     * @param string $address An email address
     * @return \MailGun\Email
     */
    public function setFrom($address, $name = null) {
        if (!stristr($address, $this->_domain)) {
            trigger_error('From address should contain the sending domain or risk being marked as spam');
        }

        $this->_data['from'] = (!is_null($name)) ? "$name <$address>" : $address;

        return $this;
    }

    /**
     * @param string|array $address An email address or array of email addresses
     * @return \MailGun\Email
     */
    public function addTo($address, $name = null) {
        $this->_addRecipient('to', $address, $name);

        return $this;
    }

    /**
     * @param string|array $address An email address or array of email addresses
     * @return \MailGun\Email
     */
    public function addCc($address, $name = null) {
        $this->_addRecipient('cc', $address, $name);

        return $this;
    }

    /**
     * @param string|array $address An email address or array of email addresses
     * @return \MailGun\Email
     */
    public function addBcc($address, $name = null) {
        $this->_addRecipient('bcc', $address, $name);

        return $this;
    }

    /**
     * @param string $type to, cc, or bcc
     * @param string $address Email address
     * @return \MailGun\Email
     * @throws \MailGun\Exception
     */
    private function _addRecipient($type, $address, $name) {
        if (!in_array($type, array('to', 'cc', 'bcc'))) {
            throw new Exception('Invalid recipient type');
        }
        if (is_string($address)) {
            $this->_data[$type][] = (!is_null($name)) ? "$name <$address>" : $address;
        } elseif (is_array($address)) {
            foreach ($address as $value) {
                $this->_addRecipient($type, $value, $name);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return \MailGun\Email
     */
    public function addHeader($name, $value) {
        $this->_data["h:$name"] = $value;

        return $this;
    }

    /**
     * @param string $address Email address to reply to
     * @return \MailGun\Email
     */
    public function setReplyTo($address, $name = null) {
        $this->addHeader('Reply-To', (!is_null($name)) ? "$name <$address>" : $address);

        return $this;
    }

    /**
     * @param string $subject Email subject
     * @return \MailGun\Email
     */
    public function setSubject($subject) {
        $this->_data['subject'] = $subject;

        return $this;
    }

    /**
     * @param string $body HTML portion of email's content
     * @return \MailGun\Email
     */
    public function setHtml($body) {
        $this->_data['html'] = $body;

        return $this;
    }

    /**
     * @param string $body Text portion of email's content
     * @return \MailGun\Email
     */
    public function setText($body) {
        $this->_data['text'] = $body;

        return $this;
    }

    /**
     * @param bool $bool
     * @return \MailGun\Email
     */
    public function setTestMode($bool) {
        $this->_data['o:testmode'] = ($bool) ? 'yes' : 'no';

        return $this;
    }

    /**
     * @param \DateTime $time When the message is scheduled to be sent
     * @throws \MailGun\Exception
     * @return \MailGun\Email
     */
    public function scheduleDelivery(\DateTime $time) {
        if (time() > $time->format('U')) {
            throw new Exception('Scheduled delivery date must be in the future');
        }
        $this->_data['o:deliverytime'] = $time->format('r');

        return $this;
    }

    /**
     * @param bool $bool
     * @return \MailGun\Email
     */
    public function enableDKIM($bool) {
        $this->_data['o:dkim'] = ($bool) ? 'yes' : 'no';

        return $this;
    }

    /**
     * @param bool $bool
     * @return \MailGun\Email
     */
    public function enableTracking($bool) {
        $this->_data['o:tracking'] = ($bool) ? 'yes' : 'no';

        return $this;
    }

    /**
     * @param bool $bool
     * @return \MailGun\Email
     */
    public function trackOpens($bool) {
        $this->_data['o:tracking-opens'] = ($bool) ? 'yes' : 'no';

        return $this;
    }

    /**
     * @param string|bool $mode
     * @return \MailGun\Email
     */
    public function trackClicks($mode) {
        if ($mode != 'htmlonly') {
            $this->_data['o:tracking-clicks'] = ($mode) ? 'yes' : 'no';
        } else {
            $this->_data['o:tracking-clicks'] = $mode;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getTags() {
        if (!array_key_exists('o:tag', $this->_data)) {
            return array();
        }

        return $this->_data['o:tag'];
    }

    /**
     * @param string $value
     * @return \MailGun\Email
     * @throws \MailGun\Exception
     */
    public function addTag($value) {
        if (count($this->getTags()) < 3) {
            if (strlen($value) > 128) {
                throw new Exception('Tags must be 128 characters or less');
            }

            $this->_data['o:tag'][] = $value;
        } else {
            throw new Exception('Only 3 tags may be applied to a message');
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $value A JSON string
     * @return \MailGun\Email
     */
    public function addVariable($name, $value) {
        $this->_data["v:$name"] = $value;

        return $this;
    }

    /**
     * @param string $path
     * @param bool $inline
     * @return \MailGun\Email
     * @throws \MailGun\Exception
     */
    public function addAttachment($path, $inline = false) {
        if (!is_readable($path)) {
            throw new Exception('Unable to attach ' . $path . '. Unable to read file or file does not exist');
        }

        if ($inline) {
            $this->_data['inline'][] = '@' . $path;
        } else {
            $this->_data['attachment'][] = '@' . $path;
        }

        return $this;
    }

    /**
     * @param string $campaignId
     * @throws \MailGun\Exception
     */
    public function addToCampaign($campaignId) {
        if (strlen($campaignId) > 64) {
            throw new Exception('Invalid campaign ID');
        }

        $this->_data['o:campaign'][] = $campaignId;
    }

    /**
     * @return \MailGun\HttpResponse
     * @throws \MailGun\Exception
     */
    public function send() {
        $url = self::API_URL . $this->_domain . '/messages';

        if (!array_key_exists('to', $this->_data) || !array_key_exists('from', $this->_data)
            || !array_key_exists('subject', $this->_data)
            || !(array_key_exists('text', $this->_data) || array_key_exists('html', $this->_data))
        ) {
            throw new Exception('Unable to send message. Required fields are missing');
        }

		$post = array();
		foreach ($this->_data as $key => $data) {
            if (is_array($data)) {
                foreach ($data as $index => $value) {
                    $post[$key . '[' . ($index + 1) . ']'] = $value;
                }
            } else {
                $post[$key] = $data;
            }
        }

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $this->_apiKey);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		$response = new HttpResponse(curl_exec($ch), curl_getinfo($ch));
		curl_close($ch);

		return $response;
	}
}
