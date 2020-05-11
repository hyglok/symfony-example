<?php
namespace Lib\Logger;

/**
 * Class RequestIdentifier
 */
class RequestIdentifier
{
    private $id;

    /**
     * RequestIdentifier constructor.
     */
    public function __construct()
    {
        $this->id = uniqid('', true);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null
     */
    public function reset()
    {
        $this->id = uniqid('', true);
    }
}
