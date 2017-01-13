<?php

/**
 * Created by PhpStorm.
 * User: Dario
 * Date: 10/01/2017
 * Time: 11:33
 */
class SPODTUTORIAL_CMP_Tutorial extends  BASE_CLASS_Widget
{
    private $assignedChallengesService;
    private $assignedChallenges;
    private $userId;
    private $challenges;
    private $progressbar;

    public function __construct(BASE_CLASS_WidgetParameter $paramObject)
    {
        parent::__construct();
        $this->challenges = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findAll();

        $this->assignedChallengesService = SPODTUTORIAL_BOL_AssignedChallengesService::getInstance();
        $this->userId =  $paramObject->additionalParamList['entityId'];

        if($this->userId == OW::getUser()->getId()) {
            $this->assignedChallenges = $this->assignedChallengesService->findByUserId($this->userId);

            if($this->assignedChallenges == null) {
                $randChallengesIdInArray = array_rand($this->challenges,5);
                $randChallengesId = array(
                    $this->challenges[$randChallengesIdInArray[0]]->id,
                    $this->challenges[$randChallengesIdInArray[1]]->id,
                    $this->challenges[$randChallengesIdInArray[2]]->id,
                    $this->challenges[$randChallengesIdInArray[3]]->id,
                    $this->challenges[$randChallengesIdInArray[4]]->id

                );
                $this->assignedChallengesService->assign($this->userId,$randChallengesId);
                $this->assignedChallenges = $this->assignedChallengesService->findByUserId($this->userId);
            }
        }

        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('spodtutorial')->getStaticJsUrl() . 'challenge.js', 'text/javascript');
        /*$js = UTIL_JsGenerator::composeJsString('
                SPODTUTORIAL.ajax_show_about = {$ajax_show_about}
            ', array(
            'ajax_show_about' => OW::getRouter()->urlFor('SPODTUTORIAL_CTRL_AjaxChallenge', 'showAbout')
        ));*/
        $js = "
SPODTUTORIAL.showFloatBox = function (id)
{
    var params = {id:id};
    previewFloatBox = OW.ajaxFloatBox('SPODTUTORIAL_CMP_InfoBox', {id:params} , {iconClass: 'ow_ic_add', title: '".OW::getLanguage()->text('spodtutorial','infobox_title')."'});
};
";

        OW::getDocument()->addOnloadScript($js);

    }
    public static function getStandardSettingValueList() // If you redefine this method, you will be able to set default values for the standard widget settings.
    {
        return array(
            self::SETTING_TITLE => OW::getLanguage()->text('spodtutorial','widget_title'),
            self::SETTING_SHOW_TITLE => true,
            self::SETTING_ICON => self::ICON_FLAG
        );

    }

    public function onBeforeRender()
    {
        $this->assign('components_url', SPOD_COMPONENTS_URL);
        $this->assign('flag',true);

        if($this->userId == OW::getUser()->getId()) {
            $this->assign('firstChallengeId',$this->assignedChallenges->firstChallengeId);
            $this->assign('secondChallengeId',$this->assignedChallenges->secondChallengeId);
            $this->assign('thirdChallengeId',$this->assignedChallenges->thirdChallengeId);
            $this->assign('fourthChallengeId',$this->assignedChallenges->fourthChallengeId);
            $this->assign('fifthChallengeId',$this->assignedChallenges->fifthChallengeId);
        }
        else{
            $this->assign('flag',false);
        }
    }
}