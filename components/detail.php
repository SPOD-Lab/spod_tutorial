<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 24/01/2017
 * Time: 15:48
 */
class SPODTUTORIAL_CMP_Detail extends OW_Component
{
    public function __construct($params)
    {
        $userId = (int) $params['userId'];
        $passedChallengesId = json_decode(SPODTUTORIAL_BOL_ProgressService::getInstance()->findByUserId($userId)->passedChallengesId);
        $challenges = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findByIdList($passedChallengesId);
        $this->assign('challenges',$challenges);

        $this->assign('components_url', SPODPR_COMPONENTS_URL);
        $this->assign('prefix','spodtutorial+');
    }
}