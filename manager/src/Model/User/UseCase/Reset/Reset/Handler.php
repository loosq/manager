<?php

namespace App\Model\User\UseCase\Signup\Reset\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Entity\Flusher;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\ResetTokenSender;
use App\Model\User\Service\ResetTokenizer;

class Handler
{
    private $users;
    private $hasher;
    private $flusher;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByResetToken($command->token)) {
            throw new \DomainException('Incorrect or confirmed token');
        }

        $user->passwordReset(
            new \DateTimeImmutable(),
            $this->hasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}