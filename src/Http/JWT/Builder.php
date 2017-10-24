<?php 

namespace Securibox\CloudAgents\Http\JWT;
use DateTimeImmutable;
/**
 * This class makes easier the token creation process
 *
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 * @since 0.1.0
 */
final class Builder
{
    /**
     * The token header
     *
     * @var array
     */
    private $headers = ['typ'=> 'JWT', 'alg' => 'none'];
    /**
     * The token claim set
     *
     * @var array
     */
    private $claims = [];
    /**
     * The data encoder
     *
     * @var Parsing\Encoder
     */
    private $encoder;
    /**
     * Initializes a new builder
     */
    public function __construct($encoder = null)
    {
        $this->encoder = $encoder;
        if($this->encoder == null){
            $this->encoder = new Parsing\Parser();
        }
    }
    /**
     * Appends a new audience
     */
    public function permittedFor($audience)
    {
        $audiences = [];
        if(isset($this->claims[RegisteredClaims::AUDIENCE]))
            $audiences = $this->claims[RegisteredClaims::AUDIENCE];
        if (! \in_array($audience, $audiences)) {
            $audiences[] = $audience;
        }
        return $this->setClaim(RegisteredClaims::AUDIENCE, $audiences);
    }
    /**
     * Configures the expiration time
     */
    public function expiresAt($expiration)
    {
        return $this->setClaim(RegisteredClaims::EXPIRATION_TIME, $expiration);
    }
    /**
     * Configures the token id
     */
    public function identifiedBy($id)
    {
        return $this->setClaim(RegisteredClaims::ID, $id);
    }
    /**
     * Configures the time that the token was issued
     */
    public function issuedAt($issuedAt)
    {
        return $this->setClaim(RegisteredClaims::ISSUED_AT, $issuedAt);
    }
    /**
     * Configures the issuer
     */
    public function issuedBy($issuer)
    {
        return $this->setClaim(RegisteredClaims::ISSUER, $issuer);
    }
    /**
     * Configures the time before which the token cannot be accepted
     */
    public function canOnlyBeUsedAfter($notBefore)
    {
        return $this->setClaim(RegisteredClaims::NOT_BEFORE, $notBefore);
    }
    /**
     * Configures the subject
     */
    public function relatedTo($subject)
    {
        return $this->setClaim(RegisteredClaims::SUBJECT, $subject);
    }
    /**
     * Configures a header item
     */
    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }
    /**
     * Configures a claim item
     *
     * @throws \InvalidArgumentException When trying to set a registered claim
     */
    public function withClaim($name, $value)
    {
        if (\in_array($name, RegisteredClaims::ALL, true)) {
            throw new \InvalidArgumentException('You should use the correct methods to set registered claims');
        }
        return $this->setClaim($name, $value);
    }

    private function setClaim($name, $value)
    {
        $this->claims[$name] = $value;
        return $this;
    }

    private function encode($items)
    {        
        return $this->encoder->base64UrlEncode(
            $this->encoder->jsonEncode($items)
        );
    }
    /**
     * Returns a signed token to be used
     */
    public function getToken($signer, $key)
    {
        $headers        = $this->headers;
        $headers['alg'] = $signer->getAlgorithmId();
        $encodedHeaders = $this->encode($headers);
        $encodedClaims  = $this->encode($this->formatClaims($this->claims));
        $signature        = $signer->sign($encodedHeaders . '.' . $encodedClaims, $key);
        $encodedSignature = $this->encoder->base64UrlEncode($signature);
        return new Plain(
            new DataSet($headers, $encodedHeaders),
            new DataSet($this->claims, $encodedClaims),
            new Signature($signature, $encodedSignature)
        );
    }
    private function formatClaims($claims)
    {
        if (isset($claims[RegisteredClaims::AUDIENCE][0]) && ! isset($claims[RegisteredClaims::AUDIENCE][1])) {
            $claims[RegisteredClaims::AUDIENCE] = $claims[RegisteredClaims::AUDIENCE][0];
        }
        foreach (\array_intersect(RegisteredClaims::DATE_CLAIMS, \array_keys($claims)) as $claim) {
            $claims[$claim] = $this->convertDate($claims[$claim]);
        }
        return $claims;
    }
    /**
     * @return int|string
     */
    private function convertDate($date)
    {
        if(!is_a($date, 'DateTime')){
            $val = $date;
            $date = new \DateTime();
            $date->setTimestamp($val);
        }
        $seconds      = $date->format('U');
        $microseconds = $date->format('u');
        if ((int) $microseconds === 0) {
            return (int) $seconds;
        }
        return $seconds . '.' . $microseconds;
    }
}