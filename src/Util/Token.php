<?php
declare(strict_types=1);

namespace App\Util;

use Cake\Utility\Security;

/**
 * Undocumented class
 */
class Token
{
    protected string $token;

    /**
     * コンストラクタです。
     *
     * @param string|null $token トークン
     */
    public function __construct(?string $token = null)
    {
        if ($token) {
            $this->token = $token;
        } else {
            $this->token = $this->generateToken();
        }
    }

    /**
     * トークンを取得します。
     *
     * @return string
     */
    public function get(): string
    {
        return $this->token;
    }

    /**
     * トークンを設定します。
     *
     * @param string $token トークン
     */
    public function set(string $token): void
    {
        $this->token = $token;
    }

    /**
     * トークンを再生成します。
     *
     * @return string
     */
    public function regenerateToken(): string
    {
        $this->token = $this->generateToken();

        return $this->get();
    }

    /**
     * トークンに対してのチェックサムを取得します。
     *
     * @return string
     */
    public function getCheckSum(): string
    {
        return md5(Security::getSalt() . $this->get());
    }

    /**
     * トークンの整合性を検証します。
     *
     * @param string $sum チェックサム
     * @return bool
     */
    public function checkToken(string $sum): bool
    {
        return $this->getCheckSum() === $sum;
    }

    /**
     * トークンを生成します。
     *
     * @param int $length 桁数
     * @return string
     */
    private function generateToken(int $length = 6): string
    {
        $max = pow(10, $length) - 1;

        return sprintf("%0{$length}d", rand(0, $max));
    }
}
