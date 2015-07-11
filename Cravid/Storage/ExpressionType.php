<?php

namespace Cravid\Storage;

class ExpressionType
{
	/**
     * The filter expression types.
     * 
     * @var int
     */
    const EQ       = 1;
    const GT       = 2;
    const GTE      = 3;
    const LT       = 4;
    const LTE      = 5;
    const NE       = 6;
    const CONTAINS = 7;
}