<?php //namespace App\Tests\Db;
 namespace App\Tests;
use App\Tests\AcceptanceTester;

class UserBookCest
{
    public function testTwoUsersInDb(AcceptanceTester $I)
    {
        $I->seeInRepository('App\Entity\User', [
            'username' => 'user',
        ]);
        $I->seeInRepository('App\Entity\User', [
            'username' => 'admin',
        ]);
    }

    public function testTwoBookInDb(AcceptanceTester $I)
    {
        $I->seeInRepository('App\Entity\Book', [
            'title' =>"Java for Beginners"
        ]);
        $I->seeInRepository('App\Entity\Book', [
            'title' => "Creative Story Writing"
        ]);
    }
}
