<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class SearchProduct
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
    public $price;

    /**
     * @var Category[]
     */
    public $categories = [];

    // /**
    //  * @var boolean
    //  */
    // public $inStock = false;
}
