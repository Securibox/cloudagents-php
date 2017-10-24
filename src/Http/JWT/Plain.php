<?php
namespace Securibox\CloudAgents\Http\JWT;
use DateTimeInterface;
/**
 * Basic structure of the JWT
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 0.1.0
 */
final class Plain
{
    /**
     * The token headers
     *
     * @var DataSet
     */
    private $headers;
    /**
     * The token claim set
     *
     * @var DataSet
     */
    private $claims;
    /**
     * The token signature
     *
     * @var Signature
     */
    private $signature;
    public function __construct(
        DataSet $headers,
        DataSet $claims,
        Signature $signature
    ) {
        $this->headers   = $headers;
        $this->claims    = $claims;
        $this->signature = $signature;
    }
    /**
     * Returns the token headers
     */
    public function headers()
    {
        return $this->headers;
    }
    /**
     * {@inheritdoc}
     */
    public function claims()
    {
        return $this->claims;
    }
    /**
     * {@inheritdoc}
     */
    public function signature()
    {
        return $this->signature;
    }
    /**
     * {@inheritdoc}
     */
    public function payload()
    {
        return $this->headers . '.' . $this->claims;
    }
    /**
     * Returns if the token is allowed to be used by the audience
     */
    public function isPermittedFor($audience)
    {
        return \in_array($audience, $this->claims->get(RegisteredClaims::AUDIENCE, []), true);
    }
    /**
     * Returns if the token has the given id
     */
    public function isIdentifiedBy($id)
    {
        return $this->claims->get(RegisteredClaims::ID) === $id;
    }
    /**
     * Returns if the token has the given subject
     */
    public function isRelatedTo($subject)
    {
        return $this->claims->get(RegisteredClaims::SUBJECT) === $subject;
    }
    /**
     * Returns if the token was issued by any of given issuers
     */
    public function hasBeenIssuedBy(...$issuers)
    {
        return \in_array($this->claims->get(RegisteredClaims::ISSUER), $issuers, true);
    }
    /**
     * Returns if the token was issued before of given time
     */
    public function hasBeenIssuedBefore($now)
    {
        return $now >= $this->claims->get(RegisteredClaims::ISSUED_AT);
    }
    /**
     * Returns if the token minimum time is before than given time
     */
    public function isMinimumTimeBefore($now)
    {
        return $now >= $this->claims->get(RegisteredClaims::NOT_BEFORE);
    }
    /**
     * Returns if the token is expired
     */
    public function isExpired($now)
    {
        if (! $this->claims->has(RegisteredClaims::EXPIRATION_TIME)) {
            return false;
        }
        return $now > $this->claims->get(RegisteredClaims::EXPIRATION_TIME);
    }
    /**
     * Returns an encoded representation of the token
     */
    public function __toString()
    {
        return \implode(
            '.',
            [$this->headers, $this->claims, $this->signature]
        );
    }
}