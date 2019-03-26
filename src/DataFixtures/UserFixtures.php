<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;
use App\Entity\Book;

use Faker\Factory;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $userUser = $this->createUser('user', 'user');

        $book1 = new Book();

        $book1->setTitle("Java for Beginners");
        $book1->setAuthor("Jake Patrck");
        $book1->setGenre("Educational");
        $book1->setBarcode("q4f7j03");
        $book1->setStartingBid(22.45);
        $book1->setBid(0);
        $book1->setBidAccepted(false);
        $book1->setComments([]);
        $book1->setAnswers([]);
        $book1->setUser($userUser);

        $userUser->addBooks($book1);
        $manager->persist($book1);

        $userAdmin = $this->createUser('admin', 'admin', ['ROLE_ADMIN']);

        $book2 = new Book();

        $book2->setTitle("Creative Story Writing");
        $book2->setAuthor("Hannah Clarke");
        $book2->setGenre("Self-help");
        $book2->setBarcode("bsd4g67");
        $book2->setStartingBid(17.75);
        $book2->setBid(0);
        $book2->setBidAccepted(false);
        $book2->setComments([]);
        $book2->setAnswers([]);
        $book2->setUser($userAdmin);

        $userAdmin->addBooks($book2);
        $manager->persist($book2);

        $manager->persist($userUser);
        $manager->persist($userAdmin);

        $faker = Factory::create();

        for($i = 0; $i<10; $i++) {
            $user = new User();

            $User_name = $faker->name;
            $User_pass = $faker->sentence(1);
            $User_Role = $faker->randomElement([['ROLE_USER'], ['ROLE_ADMIN']]);

            $user->setUsername($User_name);
            $user->setRoles($User_Role);

            // password - and encoding
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $User_pass);
            $user->setPassword($encodedPassword);

            // book assigned to the user
            $User_book = $this->createBookForUser($user);
            $user->addBooks($User_book);

            $manager->persist($User_book);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createUser($username, $plainPassword, $roles = ['ROLE_USER']):User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setRoles($roles);

        // password - and encoding
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        return $user;
    }

    private function createBookForUser($user)
    {
        $book = new Book();

        $faker = Factory::create();

        $book->setTitle($faker->sentence(rand(1, 4))); // sets the title to a random amount of words from 1-4
        $book->setAuthor($faker->firstName . " " . $faker->lastName);
        $book->setGenre($faker->randomElement(
            ['Self-help', 'Non-fiction', 'Educational', 'Biography', 'History', 'Fantasy', 'Science Fiction', 'Poetry', 'Thriller']
        ));
        $book->setBarcode($faker->asciify('*******'));
        $book->setStartingBid($faker->randomFloat(2, 4, 100)); // random float num *.** from 4 to 100
        $book->setBid(0);
        $book->setBidAccepted(false);
        $book->setComments([]);
       $book->setAnswers([]);

        $book->setUser($user);

        return $book;
    }
}