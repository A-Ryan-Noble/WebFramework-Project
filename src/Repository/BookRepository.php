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
    public function usersLatestBookTitle($users_id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :id')
            ->setParameter('id', $users_id)
            ->select('b.title')
            ->getQuery()
            ->getResult();
    }

    // Queries for the book author of a user's  book
    public function usersLatestBookAuthor($users_id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :id')
            ->setParameter('id', $users_id)
            ->select('b.author')
            ->getQuery()
            ->getResult();
    }

    // Queries to count amount of books owned by user
    public function countBooksOfUser($users_id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :id')
            ->setParameter('id', $users_id)
            ->select('COUNT(b.user)')
            ->getQuery()
            ->getSingleScalarResult();;
    }

    // Queries the owners of books
    public function getUsersBooks($userId)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :id')// user id is the books owners id
            ->setParameter('id', $userId)
            ->select('b.id')// book id
            ->getQuery()
            ->getResult();
    }

    // Queries the books of the given user and deletes them
    public function deleteUsersBooks($userId)
    {
        return $this->createQueryBuilder('b')
            ->delete()
            ->andWhere('b.user = :id')// user id is the books owners id
            ->setParameter('id', $userId)
            ->getQuery()
            ->execute();
    }
}