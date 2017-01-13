<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 12/01/2017
 * Time: 10:14
 */
class SPODTUTORIAL_BOL_AssignedChallengesService
{
    /**
     * Class instance
     *
     * @var SPODTUTORIAL_BOL_AssignedChallengesService
     */
    private static $classInstance;

    /**
     * Returns class instance
     *
     * @return $assignedChallengesDao
     */
    public static function getInstance()
    {
        if ( null === self::$classInstance )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     *
     * @var SPODTUTORIAL_BOL_AssignedChallengesDao
     */
    private $assignedChallengesDao;

    private function __construct()
    {
        $this->assignedChallengesDao = SPODTUTORIAL_BOL_AssignedChallengesDao::getInstance();
    }

    /**
     * @param $userId int the user's id
     * @return SPODTUTORIAL_BOL_AssignedChallenges challenges id assigned to actual user
     */
    public function findByUserId ( $userId ) {
        $id = (int) $userId;
        $example = new OW_Example();
        $example->andFieldEqual('userId',$id);

        $assignedChallenges = $this->assignedChallengesDao->findObjectByExample($example);
        return $assignedChallenges;
    }

    /**
     * @param $userId int user's id
     * @param $idList array id of the challenges assigned to actual user
     */
    public function assign( $userId, $idList )
    {
        $assignedChallenges = new SPODTUTORIAL_BOL_AssignedChallenges();
        $assignedChallenges->userId = $userId;
        $assignedChallenges->firstChallengeId = $idList[0];
        $assignedChallenges->secondChallengeId = $idList[1];
        $assignedChallenges->thirdChallengeId = $idList[2];
        $assignedChallenges->fourthChallengeId = $idList[3];
        $assignedChallenges->fifthChallengeId = $idList[4];

        //OW::getFeedback()->info("Hai nuove challenges!!!");
        return $this->assignedChallengesDao->save($assignedChallenges);
    }

    public function deleteAssignedChallengesById($id)
    {
        $this->assignedChallengesDao->deleteById((int) $id);
    }

}