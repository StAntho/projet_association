<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class SearchAnimal
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $word = '';

    /**
     * @var int|null
     */
    public $age;

    /**
     * @var Type[]
     */
    public $categories = [];

    /**
     * @var boolean
     */
    public $sterilised = false;
}
