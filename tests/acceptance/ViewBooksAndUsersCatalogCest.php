<?php

namespace App\Tests;
use App\Tests\AcceptanceTester;
use Codeception\Example;

class ViewBooksAndUsersCatalogCest
{
    /**
     * @example(id="1")
     * @example(id="2")
     * @example(id="3")
     * @example(id="4")
     */
    public function catalogViewToBookViewPage(AcceptanceTester $I, Example $example)
    {
        $I->expectTo("Go from viewing all the books viewing page to an individual book's page");

        $I->amOnPage('/books/');
        $I->comment('On catalog page.All books are being shown');
        $I->seeInTitle('Book Catalog');
        $I->see('Available Books');

        $I->amOnRoute('book_show', array('id' => $example['id']));
        $I->seeInTitle('Showing Book');
        $I->see("Details of the Book");
        $I->comment("Made it to Book ".$example['id']. "'s view page.");
    }

    /**
     * @example(id="1")
     * @example(id="2")
     */
    public function catalogViewToBasicUserViewPage(AcceptanceTester $I, Example $example)
    {
        $I->expectTo("Go from viewing all the users viewing page to an individual user's page");

        $I->amOnPage('/users');
        $I->comment('On catalog page.All users are being shown');

        $I->amOnRoute('user_index');

        $I->amOnRoute('user_show', array('id' => $example['id']));
        $I->comment("Made it to user ".$example['id']. "'s view page.");
    }
}