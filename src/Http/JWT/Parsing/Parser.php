<?php
namespace Securibox\CloudAgents\Http\JWT\Parsing;



/**
 * An utilitarian class that encodes and decodes data according with JOSE specifications
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 2.1.0
 */
final class Parser
{
    /**
     * Encodes to JSON, validating the errors
     *
     * @param mixed $data
     *
     * @return string
     *
     * @throws Exception When something goes wrong while encoding
     */
    public function jsonEncode($data)
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->verifyJsonError('Error while encoding to JSON');
        return $json;
    }
    /**
     * Decodes from JSON, validating the errors
     *
     * @param string $json
     * @return mixed
     *
     * @throws Exception When something goes wrong while decoding
     */
    public function jsonDecode($json)
    {
        $data = json_decode($json, true);
        $this->verifyJsonError('Error while decoding from JSON');
        return $data;
    }
    /**
     * Throws a parsing exception when an error happened while encoding or decoding
     *
     * @param string $message
     *
     * @throws Exception
     */
    private function verifyJsonError($message)
    {
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Exception(sprintf('%s: %s', $message, json_last_error_msg()));
        }
    }
    /**
     * Encodes to base64url
     *
     * @param string $data
     *
     * @return string
     *
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlEncode($data)
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }
    /**
     * Decodes from Base64URL
     *
     * @param string $data
     *
     * @return string
     *
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlDecode(string $data)
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}