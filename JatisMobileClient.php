<?php

namespace App;

/**
 * Contributed by teguh02 on github.com
 *
 * This class and all method is following this
 * jatis mobile documentation
 * https://pdfhost.io/v/2OwufmI4q_Technical_Specification_Design
 */

use Exception;

class JatisMobileClient
{

    const API_URL = "https://interactive.jatismobile.com";

    /**
     * To catch all json response from server
     */
    public static function catch_all_webhook()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Catch all json data from webhook
            $response = file_get_contents('php://input');

            // Decode json data
            $result = (array) json_decode($response);

            if (isset($result['messages'])) {
                return (array) $result['messages'];
            }

            return $result;
        }

        //return throw new Exception("MUST POST Method");
    }

    /**
     * You can use the messages node to change
     * the status of incoming message to read
     */
    public static function marking_message_as_read($status = 'read', $messageId, $url = "/wa/messages")
    {
        $data = array('status' => $status);
        return (array) self::_put($data, [], self::API_URL . '/' . $url . '/' . $messageId);
    }

    /**
     * To get Authorization token
     */
    public static function login($username, $password, $url = "/wa/users/login")
    {
        $token = base64_encode($username . ":" . $password);
        $result = (array) self::_post(
            [],
            [
                'Authorization: Basic ' . $token
            ],
            self::API_URL . $url
        );

        if (isset($result['users'])) {
            return $result['users'];
        }

        return $result;
    }

    /**
     * Use the media node to retrieve the media.
     */
    public static function downloading_media($media_id, $header = [], $url = "/v1/media")
    {
        $result = (array) self::_get($header, self::API_URL . $url . '/' . $media_id);
        return $result;
    }

    /**
     * Use the contacts node to manage WhatsApp users in your database by validating them before
     * sending messages and verify a user's identity with identity hashes.
     */
    public static function validate_contact($contact, $header = [], $blocking = 'wait', $force_check = false, $url = "/v1/contacts")
    {
        $body = [
            'blocking' => $blocking,
            'force_check' => $force_check,
        ];

        if (is_array($contact)) {
            $body['contacts'] = $contact;
        } else {
            $body['contacts'] = [$contact];
        }

        $result = (array) self::_post(
            $body,
            $header,
            self::API_URL . $url
        );

        if (isset($result['contacts'])) {
            return $result['contacts'];
        }

        return $result;
    }

    /**
     * Use the messages endpoint to send text messages to your customers.
     */
    public static function send_message(array $body = [], $header, $url = "/v1/messages")
    {
        // return $body;
        $result = (array) self::_post(
            $body,
            $header,
            self::API_URL . $url
        );

        if (isset($result['messages'])) {
            return $result['messages'];
        }

        return $result;
    }

    /**
     * Update or reset your application settings using API calls to the /wa/settings/application
     * endpoint.
     */
    public static function update_application_settings(array $body = [], $header = [], $url = "/wa/settings/application")
    {
        $results = self::_patch($body, $header, self::API_URL . $url);
        return $results;
    }

    /**
     * Check the status of your WhatsApp Business
     * API client with the health node.
     */
    public static function health_check($header = [], $url = "/wa/health")
    {
        $result = (array) self::_get($header, self::API_URL . $url);
        return $result;
    }

    private static function _post(array $data = [], array $header = array('Content-Type: application/json'), $url)
    {
        $data_json = json_encode($data);
        $header = $header;
        array_push($header, 'Content-Length: ' . strlen($data_json));
        array_push($header, 'Accept: application/json');
        array_push($header, 'Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }

    private static function _get(array $header = array('Content-Type: application/json'), $url)
    {
        $header = $header;
        array_push($header, 'Accept: application/json');
        array_push($header, 'Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }

    private static function _patch(array $data = [], array $header = array('Content-Type: application/json'), $url)
    {
        $data_json = json_encode($data);
        array_push($header, 'Content-Length: ' . strlen($data_json));
        array_push($header, 'Accept: application/json');
        array_push($header, 'Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }

    private static function _put(array $data = [], array $header = array('Content-Type: application/json'), $url)
    {
        $data_json = json_encode($data);
        array_push($header, 'Content-Length: ' . strlen($data_json));
        array_push($header, 'Accept: application/json');
        array_push($header, 'Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }
}
