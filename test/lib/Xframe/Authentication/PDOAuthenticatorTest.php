<?php

namespace Xframe\Authentication;

use PDO;
use PHPUnit\Framework\TestCase;

class PDOAuthenticatorTest extends TestCase
{
    /**
     * @var PDO
     */
    public $pdo;

    /**
     * @var Authenticator
     */
    public $authenticator;

    protected function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->authenticator = new PDOAuthenticator($this->pdo, 'test_user', 'test_email', 'test_password');
    }

    private function emptyTableProvider()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS test_user ('
            . 'test_email TEXT NOT NULL,'
            . 'test_password TEXT NOT NULL'
            . ')');
    }

    private function insertRow(string $email, string $password)
    {
        $stmt = $this->pdo->prepare('INSERT INTO test_user (test_email, test_password)'
            . ' VALUES (:email, :password)');

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
    }

    public function testTableNotFound()
    {
        $result = $this->authenticator->authenticate('identity', 'credential');

        $this->assertEquals(Result::GENERAL_FAILURE, $result->getCode());
        $this->assertStringEndsWith('no such table: test_user', $result->getMessages()['message']);
    }

    public function testTableNotFoundWithoutExceptionFlag()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_NONE);

        $result = $this->authenticator->authenticate('identity', 'credential');

        $this->assertEquals(Result::GENERAL_FAILURE, $result->getCode());
        $this->assertEquals('Could not prepare statement for Authentication', $result->getMessages()['message']);
    }

    public function testEmptyTable()
    {
        $this->emptyTableProvider();

        $result = $this->authenticator->authenticate('identity', 'credential');

        $this->assertEquals(Result::IDENTITY_NOT_FOUND, $result->getCode());
    }

    public function testAmbiguous()
    {
        $this->emptyTableProvider();
        $this->insertRow('ambig', 'pass1');
        $this->insertRow('ambig', 'pass2');

        $result = $this->authenticator->authenticate('ambig', 'pass');

        $this->assertEquals(Result::AMBIGUOUS_IDENTITY, $result->getCode());
    }

    public function testInvalid()
    {
        $this->emptyTableProvider();
        $this->insertRow('invalid', 'pass');

        $result = $this->authenticator->authenticate('invalid', 'password');

        $this->assertEquals(Result::INVALID_CREDENTIAL, $result->getCode());
    }

    public function testValid()
    {
        $this->emptyTableProvider();
        $this->insertRow('valid', 'pass');

        $result = $this->authenticator->authenticate('valid', 'pass');

        $this->assertEquals(Result::SUCCESS, $result->getCode());
    }
}
