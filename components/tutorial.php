<?php

/**
 * Created by PhpStorm.
 * User: Dario
 * Date: 10/01/2017
 * Time: 11:33
 */
class SPODTUTORIAL_CMP_Tutorial extends  BASE_CLASS_Widget
{
    private $progressService;
    private $progress;
    private $userId;
    private $challenges;
    private $challengesId;
    private $progressbar;
    private $util;
    private $newPassed;
    private $reputationRecord;

    public function __construct(BASE_CLASS_WidgetParameter $paramObject)
    {
        parent::__construct();
        $this->challenges = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findAll();
        $this->challengesId = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findIdListByExample(new OW_Example());
        $this->util = SPODTUTORIAL_CLASS_Util::getInstance();

        $this->progressService = SPODTUTORIAL_BOL_ProgressService::getInstance();
        $this->userId =  $paramObject->additionalParamList['entityId'] != null ? $paramObject->additionalParamList['entityId'] : OW::getUser()->getId();

        /* In the following code it's possible to change the interaction between this plugin and SPOD Reputation plugin
         if(OW::getPluginManager()->isPluginActive('spodreputation')) {
            $this->reputationRecord = SPODREPUTATION_BOL_Service::getInstance()->findByUserId($this->userId);
            $this->assign('level',$this->reputationRecord->level);
            $this->assign('toolbarColor',SPODREPUTATION_CLASS_Evaluation::getInstance()->setLevelColor($this->reputationRecord->level));
        }
        else {
            $this->assign('toolbarColor','#0099ff');
            $this->assign('level','Beginner');
        }
        */

        $this->assign('toolbarColor','#0099ff');
        $this->assign('level',OW::getLanguage()->text('spodtutorial','completed'));

        $this->progress = $this->progressService->findByUserId($this->userId);
        $this->newPassed = 0;

        if($this->userId == OW::getUser()->getId()) {

            if($this->progress == null) { //user doesn't have challenges
                $temp = $this->util->checkDependencies($this->challenges,array());

                $randChallengesKeyOfArray = array_rand($temp!=null?$temp:$this->challenges,2); //changing the integer value you can get more or less challenges
                $randChallengesId = array();
                foreach ($randChallengesKeyOfArray as $key) {
                    $randChallengesId[] = $temp[$key]->id;
                }
                $this->progress = $this->progressService->assign($this->userId,$randChallengesId);
                OW::getFeedback()->info(OW::getLanguage()->text('spodtutorial','new_challenge'));
            }
            elseif ((time()-strtotime($this->progress->timestamp)) > 82800 ) { //in this case I only update daily challenges
                //taking the id of the user's passed challenges
                $passed = json_decode($this->progress->passedChallengesId);

                $temp = $this->util->checkDependencies($this->challenges,$passed);

                $randChallengesKeyOfArray = array_rand($temp!=null?$temp:$this->challenges,2); //changing the integer value you can get more or less challenges
                $randChallengesId = array();
                foreach ($randChallengesKeyOfArray as $key) {
                    $randChallengesId[] = $temp[$key]->id;
                }

                $this->progress = $this->progressService->assign($this->userId,$randChallengesId);
                OW::getFeedback()->info(OW::getLanguage()->text('spodtutorial','new_challenge'));
            }
            $this->newPassed = SPODTUTORIAL_CLASS_Checker::getInstance()->checkChallenges($this->userId);
        }

        $this->progress = $this->progressService->findByUserId($this->userId);
        $passedChallengesId = json_decode($this->progress->passedChallengesId);
        $this->progressbar = count($passedChallengesId);

        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('spodtutorial')->getStaticJsUrl() . 'challenge.js', 'text/javascript');
        $js = "
SPODTUTORIAL.showPassedChallenges = function(userId) {
    var params = {userId:userId};
    detailFloatBox = OW.ajaxFloatBox('SPODTUTORIAL_CMP_Detail', {userId:params} , {width: '50%', iconClass: 'ow_ic_add', title: '".OW::getLanguage()->text('spodtutorial','detail_title')."'});
};

SPODTUTORIAL.showFloatBox = function (id)
{
    var params = {id:id};
    previewFloatBox = OW.ajaxFloatBox('SPODTUTORIAL_CMP_InfoBox', {id:params} , {width: '25%', iconClass: 'ow_ic_add', title: '".OW::getLanguage()->text('spodtutorial','infobox_title')."'});
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

    public static function getAccess() // If you redefine this method, you'll be able to manage the widget visibility
    {
        return self::ACCESS_MEMBER;
    }

    public function onBeforeRender()
    {
        $this->assign('components_url', SPOD_COMPONENTS_URL);
        $this->assign('prefix','spodtutorial+');
        $this->assign('userId',$this->userId);
        $this->assign('value',$this->progressbar);
        $this->assign('count',count($this->challenges));
        $this->assign('newPassed',$this->newPassed);
        $this->assign('flag',true);

        if($this->userId == OW::getUser()->getId()) {
            $assignedChallenges = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findByIdList(json_decode($this->progress->assignedChallengesId));
            $this->assign('challenges',$assignedChallenges);
            //check if assigned challenges have already been passed by the user
            $colors = array();
            foreach (json_decode($this->progress->assignedChallengesId) as $assignedChallengeId) {
                if(in_array($assignedChallengeId,json_decode($this->progress->passedChallengesId))) {
                    $colors[$assignedChallengeId] = "green";
                }
                else {
                    $colors[$assignedChallengeId] = "red";
                }
            }
            $this->assign('colors',$colors);
        }
        else{
            $this->assign('flag',false);
        }
    }
}