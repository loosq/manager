<?php


namespace App\Model\User\UseCase\Signup\Reset\Reset;


class Command
{
    /**
     * @var string
     */
    public $token;
    /**
     * @var string
     */
    public $password;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}