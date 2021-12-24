<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Creditcard
{
    /**
     * @Assert\Type(
     *     type="integer",
     *     message="Your creditcard {{ value }} is not a valid {{ type }}, you must enter number only."
     * )
     * @Assert\Length(
     *      min = 16,
     *      max = 16,
     *      minMessage = "Your creditcard number must be at least {{ limit }} characters long",
     *      maxMessage = "Your creditcard number cannot be longer than {{ limit }} characters"
     * )
     */
    public $creditcardnumber;

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="Your creditcard {{ value }} is not a valid {{ type }}, you must enter number only."
     * )
     */
    public $monthExpiration;

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="Your creditcard {{ value }} is not a valid {{ type }}, you must enter number only."
     * )
     */
    public $yearExpiration;

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="Your CVV {{ value }} is not a valid {{ type }}, you must enter number only."
     * )
     * @Assert\Length(
     *      min = 3,
     *      max = 3,
     *      minMessage = "Your creditcard CVV must be at least {{ limit }} characters long",
     *      maxMessage = "Your creditcard CVV cannot be longer than {{ limit }} characters"
     * )
     */
    public $cvv;
}