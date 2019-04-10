<?php

namespace App\Tests;
use App\Tests\AcceptanceTester;

class DbFixturesLoadedCest
{
    public function userFixturesWereLoadedAlready(AcceptanceTester $I)
    {
        $fixturedDbCount =12;

        $users = $I->grabEntitiesFromRepository('App\Entity\User');
        $userCount = count($users);

        $I->assertEquals($fixturedDbCount,$userCount,'Users amount as expected ');
    }
    public function bookFixturesWereLoadedAlready(AcceptanceTester $I)
    {
        $fixturedDbCount =12;

        $books = $I->grabEntitiesFromRepository('App\Entity\Book');
        $userCount = count($books);

        $I->assertEquals($fixturedDbCount,$userCount,'Book amount as expected ');
    }
}
