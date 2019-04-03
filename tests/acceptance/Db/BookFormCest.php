<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class BookFormCest
{
    public function tryToCreateNewBooks(AcceptanceTester $I)
    {
        $books = $I->grabEntitiesFromRepository('App\Entity\Book');
        $numBooksBefore = count($books);

        $I->amOnPage('/books/new');
        $I->fillField('#title', 'Php starters');
        $I->fillField('#author', 'John Doe');
        $I->fillField('#barcode', '2e3rfer3ddf');
        $I->fillField('#genre', 'educational');
        $I->fillField('#startingBid', '13');
        $I->click('Save');

        $I->amOnPage('/books/new');
        $I->fillField('#title', 'How to code');
        $I->fillField('#author', 'Jane Doe');
        $I->fillField('#barcode', 'fd3dre3f42e');
        $I->fillField('#genre', 'educational');
        $I->fillField('#startingBid', '12.99');
        $I->click('Save');

        $books = $I->grabEntitiesFromRepository('App\Entity\Book');
        $numBooksAfter = count($books);
        $expectedResult = $numBooksBefore + 2;

        $I->assertEquals($expectedResult, $numBooksAfter);
    }
}
/*

<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class BookFormCest
{
    public function tryToCreateNewBooks(AcceptanceTester $I)
    {
        $books = $I->grabEntitiesFromRepository('App\Entity\Book');
        $numBooksBefore = count($books);

        $aTitle = 'Php starters';
        $anAuthor = 'John Doe';
        $anBarcode = '2e3rfer3ddf';
        $anGenre = 'educational';
        $anStartingBid = 13;

        $I->amOnPage('/books/new');
        $I->fillField('#title', $aTitle);
        $I->fillField('#author', $anAuthor);
        $I->fillField('#barcode', $anBarcode);
        $I->fillField('#genre', $anGenre);
        $I->fillField('#startingBid', $anStartingBid);
        $I->click('Save');

        $I->amOnPage('/books/new');
        $I->fillField('#title', $aTitle);
        $I->fillField('#author', $anAuthor);
        $I->fillField('#barcode', $anBarcode);
        $I->fillField('#genre', $anGenre);
        $I->fillField('#startingBid', $anStartingBid);
        $I->click('Save');


        $books = $I->grabEntitiesFromRepository('App\Entity\Book');
        $numBooksAfter = count($books);
        $expectedResult = $numBooksBefore + 2;

        $I->assertEquals($expectedResult, $numBooksAfter);


    }
}

 */