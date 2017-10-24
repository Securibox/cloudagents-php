<?php
namespace Securibox\CloudAgents\Http\JWT;
/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 *
 * @since 4.0.0
 */
final class DataSet
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $encoded;
    public function __construct($data, $encoded)
    {
        $this->data    = $data;
        $this->encoded = $encoded;
    }
    public function get($name, $default = null)
    {

        return $this->data[$name] ? null : $default;
    }
    public function has($name)
    {
        return \array_key_exists($name, $this->data);
    }
    public function all()
    {
        return $this->data;
    }
    public function __toString()
    {
        return $this->encoded;
    }
}