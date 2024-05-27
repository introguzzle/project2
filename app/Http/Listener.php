<?php

namespace App\Http;

use ReflectionClass;

interface A
{
    public function a();
}

interface Listener extends A
{

}

$reflection = new ReflectionClass(Listener::class);

print_r($reflection->getMethod('a'));
