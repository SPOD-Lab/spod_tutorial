<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 13/01/2017
 * Time: 10:48
 */
class SPODTUTORIAL_CMP_InfoBox extends OW_Component
{
    public function __construct($params)
    {
        $example = new OW_Example();
        $id = (int) $params['id'];
        $example->andFieldEqual('id',$id);
        $challenge = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findObjectByExample($example);

        $this->assign('title', $challenge->title);
        $this->assign('body',$challenge->body);
        $this->assign('components_url', SPODPR_COMPONENTS_URL);
    }
}