<?php
use \AcceptanceTester;

class homeCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo('test a test page');
        $I->amOnPage('/');
        $I->see('hello');
    }
}