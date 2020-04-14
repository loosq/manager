<?php


namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\User;
use Monolog\Test\TestCase;
use App\Model\User\Entity\User\Id;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignedUpUser();
        $user->confirmSignUp();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = $this->buildSignedUpUser();

        $user->confirmSignUp();
        $this->expectExceptionMessage('User is already confirmed.');
        $user->confirmSignUp();
    }

    public function buildSignedUpUser(): User
    {
        return new User(
          Id::next(),
          new \DateTimeImmutable()
        );
    }
}
