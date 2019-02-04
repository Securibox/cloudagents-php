<?php
namespace Securibox\CloudAgents\Http\JWT;
use InvalidArgumentException;


/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 3.0.4
 */
final class Key
{
    /**
     * @var string
     */
    private $content;
    /**
     * @var string
     */
    private $passphrase;
    /**
     * @param string $content
     * @param string $passphrase
     */
    public function __construct($content, $passphrase = null)
    {
        $this->setContent($content);
        $this->passphrase = $passphrase;
    }
    /**
     * @param string $content
     *
     * @throws InvalidArgumentException
     */
    private function setContent($content)
    {
        if (\strpos($content, "-----BEGIN RSA PRIVATE KEY-----") === 0) {
            $content = $content;
        } elseif (\is_readable($content)) {  // It's a file path
            $content = \file_get_contents($content);
        } else {
          throw new \InvalidArgumentException('You must inform a valid key or key file');
        }
        $this->content = $content;
    }
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * @return string
     */
    public function getPassphrase()
    {
        return $this->passphrase;
    }
}
