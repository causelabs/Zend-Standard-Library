<?php

/**
 * DESCRIPTION
 *
 *
 * LICENSE:
 *
 * @category   HiDef
 * @package    HiDef_
 * @subpackage
 * @copyright  Copyright (c) 2010 HiDef Web Inc. (http://www.hidefweb.com)
 * @version    $Id:$
 */
class HiDef_RandomString
{
    /**
     * The default character set to choose from.
     *
     * @var string
     */
    protected $pool             = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Number of characters in the default characters pool
     *
     * @var int
     */
    protected $pool_length      = 62;

    /**
     * The length of string the client wants to receive
     *
     * @var int
     */
    protected $length           = 10;


    /**
     * The random characters returned to the client
     * @var string
     */
    protected $random_string    = '';

    public function __construct($length = 10)
    {
        $this->length       = $length;
    }

    /**
     * Static function that will use the default pool of strings to return a random string
     *
     * @static
     * @param int $length
     * @return string
     */
    public static function Get($length = 10)
    {
        $obj            = new self($length);
        return $obj->setRandomString();
    }

    /**
     * @param int $length
     * @return \HiDef_RandomString
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return (int) $this->length;
    }

    /**
     * @param string $pool
     * @return \HiDef_RandomString
     */
    public function setPool($pool)
    {
        $this->pool = $pool;
        $this->pool_length = strlen($pool);
        return $this;
    }

    /**
     * @return string
     */
    public function getPool()
    {
        return (string) $this->pool;
    }

    /**
     * @param int $pool_length
     * @return \HiDef_RandomString
     */
    public function setPoolLength($pool_length)
    {
        $this->pool_length = $pool_length;
        return $this;
    }

    /**
     * @return int
     */
    public function getPoolLength()
    {
        return (int) $this->pool_length;
    }

    /**
     *
     * @return string
     * @internal param string $random_string
     */
    public function setRandomString()
    {
        for ($i = 0; $i < $this->length; $i++) {
            $this->random_string .= $this->pool[mt_rand(0, $this->pool_length - 1)];
        }

        return (string) $this->random_string;
    }

    /**
     * @return string
     */
    public function getRandomString()
    {
        if (empty($this->random_string)) {
            $this->setRandomString();
        }

        return (string) $this->random_string;
    }


}
