<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 12/01/2017
 * Time: 09:56
 */
class SPODTUTORIAL_BOL_AssignedChallenges extends OW_Entity
{
    /**
     * @var int user's id
     */
    public $userId;

    /**
     * @var int id of the first challenge assigned to the user
     */
    public $firstChallengeId;

    /**
     * @var int id of the second challenge assigned to the user
     */
    public $secondChallengeId;

    /**
     * @var int id of the third challenge assigned to the user
     */
    public $thirdChallengeId;

    /**
     * @var int id of the fourth challenge assigned to the user
     */
    public $fourthChallengeId;

    /**
     * @var int id of the fifth challenge assigned to the user
     */
    public $fifthChallengeId;

}