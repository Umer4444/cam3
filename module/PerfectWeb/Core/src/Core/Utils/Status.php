<?php

namespace PerfectWeb\Core\Utils;

class Status
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const BANNED = 2;
    const PENDING = 3;
    const REJECTED = 4;
    const DRAFT = 5;

    const INACTIVE_TEXT = 'inactive';
    const ACTIVE_TEXT = 'active';
    const BANNED_TEXT = 'banned';
    const PENDING_TEXT = 'pending';
    const REJECTED_TEXT = 'rejected';
    const DRAFT_TEXT = 'draft';

    /**
     * @param $fromWhat
     *
     * @return string
     * @throws \Exception
     */
    static function getFrom($fromWhat)
    {
        switch ($fromWhat) {
            case self::INACTIVE:
                return self::INACTIVE_TEXT;
            break;
            case self::ACTIVE:
                return self::ACTIVE_TEXT;
            break;
            case self::BANNED:
                return self::BANNED_TEXT;
            break;
            case self::PENDING:
                return self::PENDING_TEXT;
            break;
            case self::REJECTED:
                return self::REJECTED_TEXT;
            break;
            case self::DRAFT:
                return self::DRAFT_TEXT;
            break;
        }

        throw new \Exception('This status is not defined !');

    }

    static function getStatusValues()
    {

        $array      = [];
        $reflection = new \ReflectionClass(self::class);
        $constats   = array_filter(array_flip($reflection->getConstants()), function($value){
            return strpos($value, '_TEXT') === false;
        });

        foreach ($constats as $status => $value) {
            $array[$status] = self::getFrom($status);
        }

        return $array;

    }

}