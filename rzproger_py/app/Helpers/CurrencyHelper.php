<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Форматирует цену в кыргызских сомах
     *
     * @param float $amount Сумма в кыргызских сомах
     * @return string Форматированная сумма с символом валюты
     */
    public static function formatKgs($amount)
    {
        return number_format($amount, 0, '.', ' ') . ' сом';
    }

    /**
     * Для совместимости со старым кодом, просто форматирует сумму в сомах
     *
     * @param float $amount Сумма в сомах
     * @return string Форматированная сумма в кыргызских сомах
     */
    public static function convertAndFormat($amount)
    {
        return self::formatKgs($amount);
    }

    /**
     * Для совместимости, просто возвращает сумму без изменений
     *
     * @param float $amount Сумма в сомах
     * @return float Сумма в кыргызских сомах
     */
    public static function usdToKgs($amount)
    {
        return $amount;
    }
}
