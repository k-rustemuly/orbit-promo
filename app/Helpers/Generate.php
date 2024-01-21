<?php

namespace App\Helpers;

class Generate
{
    /**
     * Генерирует пароль в виде "111111" с заданной длиной.
     *
     * @param int $length
     * @return string
     */
    public static function code(int $length = 6)
    {
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        $password = mt_rand($min, $max);

        return $password;
    }

}
