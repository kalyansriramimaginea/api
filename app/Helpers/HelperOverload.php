<?php

use Illuminate\Support\Str;

if (! function_exists('str_singular')) {

    function str_singular($value)
    {
        switch ($value) {
            case 'trivias':
            case 'trivia':
            case 'Trivia':
                return 'trivia';
                break;
            default:
                return Str::singular($value);
                break;
        }
    }

}
