<?php

/**
 * MailGun HTTP Response
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

/**
* MailGun HTTP Response
* @author Orlando Marin
*/
Namespace MailGun;
class HttpResponse
{
    protected $_data = array();

    public function __construct($response, $details) {
        $this->_data['response'] = $response;

        if ($details === false) {
            $this->_data = false;
        } else {
            foreach ($details as $key => $value) {
                $this->_data[$key] = $value;
            }
        }
    }

    /**
     * @return int|bool
     */
    public function getHttpCode() {
        $return = ($this->_data !== false) ? $this->_data['http_code'] : $this->_data;
        return $return;
    }

    /**
     * @return string
     */
    public function getResponse() {
        return $this->_data['response'];
    }

    /**
     * @return \stdClass
     */
    public function getResponseObject()
    {
        return json_decode($this->_data['response']);
    }

    /**
     * @return string|bool
     */
    public function getUrl() {
        $return = ($this->_data !== false) ? $this->_data['url'] : $this->_data;
        return $return;
    }

    /**
     * @return string|bool
     */
    public function getContentType() {
        $return = ($this->_data !== false) ? $this->_data['content_type'] : $this->_data;
        return $return;
    }

    /**
     * @return int|bool
     */
    public function getHeaderSize() {
        $return = ($this->_data !== false) ? $this->_data['header_size'] : $this->_data;
        return $return;
    }

    /**
     * @return int|bool
     */
    public function getRequestSize() {
        $return = ($this->_data !== false) ? $this->_data['request_size'] : $this->_data;
        return $return;
    }

    /**
     * @return int|bool
     */
    public function getFileTime() {
        $return = ($this->_data !== false) ? $this->_data['filetime'] : $this->_data;
        return $return;
    }

    /**
     * @return int|bool
     */
    public function getSslVerificationResult() {
        $return = ($this->_data !== false) ? $this->_data['ssl_verify_result'] : $this->_data;
        return $return;
    }

    /**
     * @return int|bool
     */
    public function getRedirectCount() {
        $return = ($this->_data !== false) ? $this->_data['redirect_count'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getTotalTime() {
        $return = ($this->_data !== false) ? $this->_data['total_time'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getLookupTime() {
        $return = ($this->_data !== false) ? $this->_data['namelookup_time'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getConnectTime() {
        $return = ($this->_data !== false) ? $this->_data['connect_time'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getPreTransferTime() {
        $return = ($this->_data !== false) ? $this->_data['pretransfer_time'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getUploadSize() {
        $return = ($this->_data !== false) ? $this->_data['size_upload'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getDownloadSize() {
        $return = ($this->_data !== false) ? $this->_data['size_download'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getDownloadSpeed() {
        $return = ($this->_data !== false) ? $this->_data['speed_download'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getUploadSpeed() {
        $return = ($this->_data !== false) ? $this->_data['speed_upload'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getUploadContentLength() {
        $return = ($this->_data !== false) ? $this->_data['upload_content_length'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getDownloadContentLength() {
        $return = ($this->_data !== false) ? $this->_data['download_content_length'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getTransferStartTime() {
        $return = ($this->_data !== false) ? $this->_data['starttransfer_time'] : $this->_data;
        return $return;
    }

    /**
     * @return float|bool
     */
    public function getRedirectTime() {
        $return = ($this->_data !== false) ? $this->_data['redirect_time'] : $this->_data;
        return $return;
    }

    /**
     * @return array|bool
     */
    public function getCertificateInformation() {
        $return = ($this->_data !== false) ? $this->_data['certinfo'] : $this->_data;
        return $return;
    }
}
