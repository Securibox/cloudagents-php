<?php
namespace Securibox\CloudAgents\Http\JWT\Signer;
use InvalidArgumentException;
/**
 * Base class for RSASSA-PKCS1 signers
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 2.1.0
 */
class Sha256 implements SignerInterface
{
    /**
     * Creates a hash for the given payload
     *
     * @param string $payload
     * @param Key $key
     *
     * @return string
     *
     * @throws InvalidArgumentException When given key is invalid
     */
    public function sign($payload, $key)
    {
        $key = \openssl_get_privatekey($key->getContent(), $key->getPassphrase());
        $this->validateKey($key);
        $signature = '';
        if (! \openssl_sign($payload, $signature, $key, $this->getAlgorithm())) {
            throw new InvalidArgumentException(
                'There was an error while creating the signature: ' . \openssl_error_string()
            );
        }
        return $signature;
    }
    /**
     * Returns if the expected hash matches with the data and key
     *
     * @param string $expected
     * @param string $payload
     * @param Key $key
     *
     * @return bool
     *
     * @throws InvalidArgumentException When given key is invalid
     */
    final public function verify($expected, $payload, $key)
    {
        $key = \openssl_get_publickey($key->getContent());
        $this->validateKey($key);
        return \openssl_verify($payload, $expected, $key, $this->getAlgorithm()) === 1;
    }
    /**
     * Raise an exception when the key type is not the expected type
     *
     * @param resource|bool $key
     *
     * @throws InvalidArgumentException
     */
    private function validateKey($key)
    {
        if ($key === false) {
            throw new InvalidArgumentException(
                'It was not possible to parse your key, reason: ' . \openssl_error_string()
            );
        }
        $details = \openssl_pkey_get_details($key);
        if (! isset($details['key']) || $details['type'] !== \OPENSSL_KEYTYPE_RSA) {
            throw new InvalidArgumentException('This key is not compatible with RSA signatures');
        }
    }

     /**
     * Returns the algorithm id
     *
     * @return string
     */
    public function getAlgorithmId()
    {
        return 'RS256';
    }
    /**
     * Returns the algorithm name
     *
     * @return int
     */
    public function getAlgorithm()
    {
        return \OPENSSL_ALGO_SHA256;
    }    
}