<?php

namespace App\Parsers;

abstract class ParserBase
{

    public abstract function parse($path): array;
}