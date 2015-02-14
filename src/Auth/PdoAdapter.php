<?php
/**
 * @link      http://github.com/jeremykendall/slim-auth Canonical source repo
 * @copyright Copyright (c) 2013 Jeremy Kendall (http://about.me/jeremykendall)
 * @license   http://github.com/jeremykendall/slim-auth/blob/master/LICENSE MIT
 */

namespace Auth;

use PDO;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;

class PdoAdapter extends AbstractAdapter
{
    /**
     * @var PDO DB connection
     */
    private $pdo;

    /**
     * @var string the table name to check
     */
    private $tableName;

    /**
     * @var string the column to use as the identity
     */
    private $identityColumn;

    /**
     * @var string column to be used as the credential
     */
    private $credentialColumn;

    /**
     * Public constructor
     *
     * @param PDO $pdo
     * @param string $tableName
     * @param string $identityColumn
     * @param string $credentialColumn
     */
    public function __construct(
        PDO $pdo,
        $tableName,
        $identityColumn,
        $credentialColumn
    ) {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->identityColumn = $identityColumn;
        $this->credentialColumn = $credentialColumn;
    }

    /**
     * Performs authentication
     *
     * @return Result Authentication result
     */
    public function authenticate()
    {
        $rows = $this->findUser();

        if ($rows === false) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                array(),
                array('User not found.')
            );
        }

        if (count($rows) > 1) {
            return new Result(Result::FAILURE_IDENTITY_AMBIGUOUS, array(), array("Ambigous"));
        }

        if (count($rows) == 1) {
            $data = $rows[0];
            $hash = $data['password'];
            if (password_verify($this->credential, $hash)) {
                $this->needsHash($hash);
                unset($data['password']);
                return new Result(Result::SUCCESS, $data, array());
            }
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, array(), array("Invalid username or password provided"));
        }

        return new Result(Result::FAILURE, array(), array('Failed'));
    }

    /**
     * Finds user to authenticate
     *
     * @return array|null Array of user data, null if no user found
     */
    private function findUser()
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE %s = :identity',
            $this->getTableName(),
            $this->getIdentityColumn()
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('identity' => $this->getIdentity()));

        return $stmt->fetchAll();
    }

    private function needsHash($hash)
    {
        $options = array('cost' => 10);
        if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
            $hash = password_hash($this->getCredential(), PASSWORD_DEFAULT, $options);
            $sql = sprintf(
                'UPDATE %s SET %s = :hash WHERE %s = :identity',
                $this->getTableName(),
                $this->getCredentialColumn(),
                $this->getIdentityColumn()
            );
            $sth = $this->pdo->prepare($sql);
            $sth->execute(array(':identity' => $this->getIdentity(), ':hash' => $hash));
        }
    }

    /**
     * Get tableName
     *
     * @return string tableName
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Get identityColumn
     *
     * @return string identityColumn
     */
    public function getIdentityColumn()
    {
        return $this->identityColumn;
    }

    /**
     * Get credentialColumn
     *
     * @return string credentialColumn
     */
    public function getCredentialColumn()
    {
        return $this->credentialColumn;
    }
}
