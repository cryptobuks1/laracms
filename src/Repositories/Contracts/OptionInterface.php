<?php

namespace Laracms\Repositories\Contracts;

interface OptionInterface
{
    public function findByName($name);
    public function findByNames($names);
}
