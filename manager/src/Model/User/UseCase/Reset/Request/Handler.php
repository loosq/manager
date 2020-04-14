<?php

namespace App\Model\User\UseCase\Signup\Reset\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Entity\Flusher;
use App\Model\User\Service\ResetTokenSender;
use App\Model\User\Service\ResetTokenizer;

class Handler
{
    private $tokenizer;
    private $flusher;
    private $sender;

    public function __construct(
        UserRepository $users,
        ResetTokenizer $tokenizer,
        Flusher $flusher,
        ResetTokenSender $sender
    )
    {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->toeknizer->generate(),
            new \DateTimeImmutable()
        );

        $this->flusher->flush();

        $this->sender->send($user->getEmail(),$user->getResetToken());
    }
}