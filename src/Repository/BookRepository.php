<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // Queries for the book title of a user's  book
    public function searchForUsersLatestBookTitle($users_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT title FROM book b
        WHERE b.user_id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $users_id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetch();
    }

    // Queries for the book author of a user's  book
    public function searchForUsersLatestBookAuthor($users_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT author FROM book b
        WHERE b.user_id = :id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $users_id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetch();
    }

    // Queries to count amount of books owned by user
    public function countBooksOfUser($users_id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :id')
            ->setParameter('id', $users_id)
            ->select('SUM(b.user)')
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}