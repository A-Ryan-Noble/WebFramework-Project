<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // create objects
        $userUser = $this->createUser('user', 'user');
        $userAdmin = $this->createUser('admin', 'admin', ['ROLE_ADMIN']);

        // store to DB
        $manager->persist($userUser);
        $manager->persist($userAdmin);
        $manager->flush();
    }

    private function createUser($username, $plainPassword, $roles = ['ROLE_USER']):User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setRoles($roles);

        // password - and encoding
        $encodedPassword = $this->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        return $user;
    }
}