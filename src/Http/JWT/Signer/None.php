<?php
declare(strict_types=1);
namespace Securibox\CloudAgents\Http\JWT\Signer;
/**
 * @author Luís Cobucci <lcobucci@gmail.com>
 * @since 4.0.0
 */
final class None implements SignerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId()
    {
        return 'none';
    }
    /**
     * {@inheritdoc}
     */
    public function sign(string $payload, Key $key)
    {
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function verify(string $expected, string $payload, Key $key)
    {
        return $expected === '';
    }
}