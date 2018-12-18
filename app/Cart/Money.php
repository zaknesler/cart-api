<?php

namespace App\Cart;

use Money\Currency;
use NumberFormatter;
use Money\Money as BaseMoney;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class Money
{
    /**
     * The base money instance.
     *
     * @var \Money\Money
     */
    protected $money;

    /**
     * Create a new money instance.
     *
     * @param int $value
     */
    public function __construct($value)
    {
        $this->money = new BaseMoney($value, new Currency('USD'));
    }

    /**
     * Return the formatted value.
     *
     * @return string
     */
    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('en_US', NumberFormatter::CURRENCY),
            new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }

    /**
     * Get the raw amount of money in cents.
     *
     * @return int
     */
    public function amount()
    {
        return $this->money->getAmount();
    }
}
