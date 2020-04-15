<?php


namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Network;
use App\Model\User\Entity\User\User;
use Monolog\Test\TestCase;
use App\Model\User\Entity\User\Id;

class AuthTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpByNetwork(
            $network = 'vk',
            $identity = '0001'
        );

        self::assertTrue($user->isActive());

        self::assertCount(1, $networks = $user->getNetworks());

        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals($network, $first->getNetwork());
        self::assertEquals($identity, $first->getIdentity());
    }

    public function testAlready(): void
    {
        $user = new User(
            $id = Id::next(),
            $email = new \DateTimeImmutable()
        );

        $user->signUpByNetwork(
            $network = 'vk',
            $identity = '0001'
        );

        $this->expectExceptionMessage('User is already signed up.');

        $user->signUpByNetwork($network, $identity);
    }
}
