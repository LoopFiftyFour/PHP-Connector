<?php

namespace Loop54\API\Examples\App\Utils;

function nonempty($string)
{
    return isset($string) && preg_match('(\S)', $string);
}
