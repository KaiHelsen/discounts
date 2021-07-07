<?php


namespace App\Model;


interface JsonDecodeable
{
    public static function fromArray(array $input): JsonDecodeable;
}