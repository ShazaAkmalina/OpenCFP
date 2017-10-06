<?php

namespace OpenCFP\Test\Infrastructure\Auth;

use OpenCFP\Infrastructure\Auth\SentryAccountManagement;
use OpenCFP\Infrastructure\Auth\SentryAuthentication;
use OpenCFP\Test\DatabaseTestCase;

class SentryAuthenticationTest extends DatabaseTestCase
{
    use SentryTestHelpers;

    /**
     * @var SentryAuthentication
     */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $accounts = new SentryAccountManagement($this->getSentry());

        $accounts->create('test@example.com', 'secret');
        $accounts->activate('test@example.com');

        $this->sut = new SentryAuthentication($this->getSentry());
    }

    /** @test */
    public function existing_user_can_authenticate()
    {
        $this->sut->authenticate('test@example.com', 'secret');
        $this->assertTrue($this->sut->check());

        $user = $this->sut->user();

        $this->assertEquals('test@example.com', $user->getLogin());
    }
}
