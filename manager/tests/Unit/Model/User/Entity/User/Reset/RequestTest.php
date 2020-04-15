<?php


namespace App\Tests\Unit\Model\User\Entity\User\SignUp\RequestTest;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use Monolog\Test\TestCase;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $user = $this->buildSignedUpUserByEmail();

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());
    }

    public function testAlready(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $user = $this->buildSignedUpUserByEmail();

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Resetting is already requested.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new \DateTimeImmutable();
        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $user = $this->buildSignedUpUserByEmail();

        $user->requestPasswordReset($token1, $now);

        self::assertEquals($token1, $user->getResetToken());

        $token2 = new ResetToken('token', $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->getResetToken());
    }

    public function testWithoutEmail(): void
    {
        $user = $this->buildSignedUpUserByNetwork();
        $now = new \DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $this->expectExceptionMessage('Email is not specified.');

        $user->requestPasswordReset($token, $now);
    }

    public function buildSignedUpUserByEmail(): User
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpByEmail(
            new Email('email@test.test'),
            'hash',
            'token'
        );

        return $user;
    }

    public function buildSignedUpUserByNetwork(): User
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpByNetwork(
            'vk',
            '000001'
        );

        return $user;
    }
}
