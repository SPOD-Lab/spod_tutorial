<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 12/01/2017
 * Time: 10:00
 */
class SPODTUTORIAL_BOL_AssignedChallengesDao extends OW_BaseDao
{
    /**
     * Constructor.
     *
     */
    protected function __construct()
    {
        parent::__construct();
    }
    /**
     * Singleton instance.
     *
     * @var SPODTUTORIAL_BOL_AssignedChallengesDao
     */
    private static $classInstance;

    /**
     * Returns an instance of class (singleton pattern implementation).
     *
     * @return SPODTUTORIAL_BOL_AssignedChallengesDao
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * @see OW_BaseDao::getDtoClassName()
     *
     */
    public function getDtoClassName()
    {
        return 'SPODTUTORIAL_BOL_AssignedChallenges';
    }

    /**
     * @see OW_BaseDao::getTableName()
     *
     */
    public function getTableName()
    {
        return OW_DB_PREFIX . 'spodtutorial_assigned_challenges';
    }

}