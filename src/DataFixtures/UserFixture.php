<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('superadmin');
        $user->setPassword($this->encoder->encodePassword($user, 'azerty33'));
        $user->setEmail('superadmin@aze.fr');
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $user1 = new User();
        $user1->setUsername('admin');
        $user1->setPassword($this->encoder->encodePassword($user, '1234'));
        $user1->setEmail('admin@aze.fr');
        $user1->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->persist($user1);
        $manager->flush();
    }
}
