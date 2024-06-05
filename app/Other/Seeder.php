<?php

namespace App\Other;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder as LaravelSeeder;
use App\Other\Contracts\Seeder as SeederContract;

abstract class Seeder
    extends LaravelSeeder
    implements SeederContract
{
    use WithoutModelEvents;
}
