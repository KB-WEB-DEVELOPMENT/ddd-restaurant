<?php

namespace Kami\Restaurant\Domain\Prices;

enum VatRate: string
{
    case Food = '0.19';
    case Drink = '0.19';
}
