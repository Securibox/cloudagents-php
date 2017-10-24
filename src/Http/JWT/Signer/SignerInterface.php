<?php
namespace Securibox\CloudAgents\Http\JWT\Signer;


/**
 * Basic interface for token signers
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 0.1.0
 */
interface SignerInterface
{
    /**
     * Returns the algorithm id
     *
     * @return string
     */
    public function getAlgorithmId();
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
    public function sign($payload, $key);
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
    public function verify($expected, $payload, $key);
}