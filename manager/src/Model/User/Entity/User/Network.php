<?php


namespace App\Model\User\Entity\User;


use Ramsey\Uuid\Uuid;

class Network
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var User
     */
    private $User;
    /**
     * @var string
     */
    private $network;
    /**
     * @var string
     */
    private $identity;

    public function __constructor(User $user, string $network, string $identity)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function isForNetwork(string $network): bool
    {
        return $this->network === $network;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }
}