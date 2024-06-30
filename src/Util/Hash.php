<?php
declare(strict_types=1);

namespace App\Util;

use Cake\Utility\Security;

/**
 * Undocumented class
 */
class Hash
{
    /**
     * Undocumented function
     *
     * @return string
     */
    public static function generate(): string
    {
        return hash(
            'sha256',
            Security::getSalt() . mt_rand(1, 999999999)
        );
    }
}
