<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 19/01/2017
 * Time: 15:33
 */
class SPODTUTORIAL_CLASS_Util
{
    private static $classInstance;

    public static function getInstance()
    {
        if(self::$classInstance === null)
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    protected function __construct() {
    }

    public function checkDependencies($remainedChallenges, $passedId) {
        $temp = array();
        foreach ($remainedChallenges as $remainedChallenge) {
            if($remainedChallenge->dependencies == "0") {
                $temp[]=$remainedChallenge;
            }
            elseif (in_array($remainedChallenge->dependencies,$passedId)) {
                $temp[]=$remainedChallenge;
            }
        }
        return $temp;
    }

}