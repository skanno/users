<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Util\Token;
use Cake\ORM\Entity;

/**
 * TempUser Entity
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Cake\I18n\DateTime|null $created
 */
class TempUser extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'email' => true,
        'token' => true,
        'created' => true,
    ];

    /**
     * tokenに対してのチェックサムを取得する仮想フィールドです。
     *
     * @return string
     */
    protected function _getCheckSum(): string
    {
        $token = new Token($this->token);

        return $token->getCheckSum();
    }
}
