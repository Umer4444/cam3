<?php

namespace PerfectWeb\Core\View\Helper;

use Defuse\Crypto\Key;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception as CryptoException;

class Crypt extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    const INSERT_POSITION = 13;

    public function __invoke()
    {
        return $this;
    }

    static public function encrypt($data)
    {

        $key = Key::createNewRandomKey();
        $asciKey = $key->saveToAsciiSafeString();
        $crypted = Crypto::encrypt($data, $key);

        return $asciKey.'-'.$crypted;

    }

    static public function decrypt($data)
    {

        list($asciKey, $encrypted) = explode('-', $data, 2);

        return Crypto::decrypt($encrypted, Key::LoadFromAsciiSafeString($asciKey));

    }

}