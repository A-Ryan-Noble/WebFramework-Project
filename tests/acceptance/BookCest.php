<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class BookCest
{
    public function tryToCreateNewBooks(AcceptanceTester $I)
    {
        $books = $I->grabEntitiesFromRepository('App\Entity\Book');
        $numBooksBefore = count($books);

        $I->amOnPage("/books/new");
        $I->fillField('#booktitle','Testing');
        $I->fillField('#book_author', 'John Doe');
        $I->fillField('#book_barcode', '2e3rfer3ddf');
        $I->fillField('#book_genre', 'educational');
        $I->fillField('#book_startingBid', 13);
        $I->click('Create Book');

        $I->amOnPage('/books/new');
        $I->fillField('#book_title', 'How to code');
        $I->fillField('#book_author', 'Jane Doe');
        $I->fillField('#book_barcode', 'fd3dre3f42e');
        $I->fillField('#book_genre', 'educational');
        $I->fillField('#book_startingBid', 12.99);
        $I->click('Create Book');

        $books = $I->grabEntitiesFromRepository('App\Entity\Book');
        $numBooksAfter = count($books);
        $expectedResult = $numBooksBefore + 2;

        $I->assertEquals($expectedResult, $numBooksAfter);
    }
}