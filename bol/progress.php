<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 12/01/2017
 * Time: 09:56
 */
class SPODTUTORIAL_BOL_Progress extends OW_Entity
{
    /**
     * @var int user's id
     */
    public $userId;

    /**
     * @var array ids of the challenges already passed by the user
     */
    public $passedChallengesId;

    /**
     * @var array ids of the challenges assigned to the user
     */
    public $assignedChallengesId;

    /**
     * @var integer timestamp of the last assigned challenges
     */
    public $timestamp;

}