<?php

function isprod()
{
    return env('APP_ENV') === 'production';
}

function isnotprod()
{
    return !isprod();
}


