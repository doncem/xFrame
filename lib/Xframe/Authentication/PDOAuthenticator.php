<?php

namespace Xframe\Authentication;

use Exception;
use PDO;

/**
 * A PDO implementation of the Authenticator.
 *
 * @package authentication
 */
class PDOAuthenticator implements Authenticator
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $identityColumn;

    /**
     * @var string
     */
    private $credentialColumn;

    /**
     * @var Result
     */
    private $result;

    /**
     * @param PDO    $pdo
     * @param string $table
     * @param string $identityColumn
     * @param string $credentialColumn
     */
    public function __construct($pdo, $table, $identityColumn, $credentialColumn)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->identityColumn = $identityColumn;
        $this->credentialColumn = $credentialColumn;
        $this->result = new Result();
    }

    /**
     * @param string $identity
     * @param string $credential
     *
     * @return Result
     */
    public function authenticate($identity, $credential)
    {
        try {
            $dbResult = $this->fetchDbResult($identity);
            $this->processDbResult($dbResult, $credential);
        } catch (Exception $ex) {
            $this->result->setCode(Result::GENERAL_FAILURE);
            $this->result->setMessages([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'trace' => $ex->getTraceAsString()
            ]);
        }

        return $this->result;
    }

    /**
     * Query a database for a specific identity.
     *
     * @param string $identity
     *
     * @return array
     */
    private function fetchDbResult($identity)
    {
        $stmt = $this->pdo->prepare("SELECT
                                        `{$this->identityColumn}`,
                                        `{$this->credentialColumn}`
                                    FROM `{$this->table}`
                                    WHERE
                                        `{$this->identityColumn}` = :identity");
        $stmt->bindParam(':identity', $identity);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }

    /**
     * Processes the result from the db and assigns appropriate codes to the authentication.
     *
     * @param array $result
     * @param array $credential
     */
    private function processDbResult($result, $credential)
    {
        $num_results = \count($result);

        if (0 === $num_results) {
            $this->result->setCode(Result::IDENTITY_NOT_FOUND);
        } elseif (1 < $num_results) {
            $this->result->setCode(Result::AMBIGUOUS_IDENTITY);
        } elseif ($result[0]->{$this->credentialColumn} !== $credential) {
            $this->result->setCode(Result::INVALID_CREDENTIAL);
        }

        $this->result->setCode(Result::SUCCESS);
    }
}
