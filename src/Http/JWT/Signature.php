<?php
namespace Securibox\CloudAgents\Http\JWT;
/**
 * This class represents a token signature
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 0.1.0
 */
final class Signature
{
    /**
     * @var string
     */
    private $hash;
    /**
     * @var string
     */
    private $encoded;
    public static function fromEmptyData()
    {
        return new self('', '');
    }
    public function __construct($hash, $encoded)
    {
        $this->hash    = $hash;
        $this->encoded = $encoded;
    }
    public function hash()
    {
        return $this->hash;
    }
    /**
     * Returns the encoded version of the signature
     */
    public function __toString()
    {
        return $this->encoded;
    }
} 