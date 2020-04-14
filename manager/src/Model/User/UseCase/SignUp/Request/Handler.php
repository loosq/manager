<?php

namespace App\Model\User\UseCase\Signup\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\ConfirmTokenizer;
use App\Model\User\Service\ConfirmTokenSender;

class Handler
{
    private $users;
    private $hasher;
    private $tokinizer;
    private $sender;
    private $flusher;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        ConfirmTokenizer $tokinizer,
        ConfirmTokenSender $sender,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->tokinizer = $tokinizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exists');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokinizer->generate()
        );

        $this->users->add($user);
        $this->sender->send($email, $token);
        $this->flusher->flush();
    }
}