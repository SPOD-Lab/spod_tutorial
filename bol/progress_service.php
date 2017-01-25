<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 12/01/2017
 * Time: 10:14
 */
class SPODTUTORIAL_BOL_ProgressService
{
    /**
     * Class instance
     *
     * @var SPODTUTORIAL_BOL_ProgressService
     */
    private static $classInstance;

    /**
     * Returns class instance
     *
     * @return $progressService SPODTUTORIAL_BOL_ProgressService
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
     * @var SPODTUTORIAL_BOL_ProgressDao
     */
    private $progressDao;

    private function __construct()
    {
        $this->progressDao = SPODTUTORIAL_BOL_ProgressDao::getInstance();
    }

    /**
     * @param $userId int the user's id
     * @return SPODTUTORIAL_BOL_Progress progress for the actual user
     */
    public function findByUserId ( $userId ) {
        $id = (int) $userId;
        $example = new OW_Example();
        $example->andFieldEqual('userId',$id);

        $progress = $this->progressDao->findObjectByExample($example);
        return $progress;
    }

    /**
     * @param $userId int user's id
     * @param $idList array id of the challenges assigned to actual user
     */
    public function assign( $userId, $idList )
    {
        if($this->findByUserId($userId) != null) {
            $progress = $this->findByUserId($userId);
        }
        else
            $progress = new SPODTUTORIAL_BOL_Progress();

        $progress->userId = $userId;
        $progress->assignedChallengesId = json_encode($idList);
        $progress->timestamp = date('Y-m-d',time());
        $this->progressDao->save($progress);
        return $progress;
    }

    public function pass( $userId, $id) {
        if($this->findByUserId($userId) != null) {
            $progress = $this->findByUserId($userId);
            $temp = json_decode($progress->passedChallengesId);
            if(!in_array("".$id,$temp)) {
                $temp[] = "" . $id;
                $progress->passedChallengesId = json_encode($temp);
                SPODTUTORIAL_BOL_ProgressDao::getInstance()->save($progress);
                return true;
            }
        }
        return false;
    }
}