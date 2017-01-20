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

    public function __construct(BASE_CLASS_WidgetParameter $paramObject)
    {
        parent::__construct();
        $this->challenges = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findAll();
        $this->challengesId = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findIdListByExample(new OW_Example());
        $this->util = SPODTUTORIAL_CLASS_Util::getInstance();

        $this->progressService = SPODTUTORIAL_BOL_ProgressService::getInstance();
        $this->userId =  $paramObject->additionalParamList['entityId'] != null ? $paramObject->additionalParamList['entityId'] : OW::getUser()->getId();

        $this->progress = $this->progressService->findByUserId($this->userId);

        if($this->userId == OW::getUser()->getId()) {

            if($this->progress == null) { //non esistono challenge per l'utente
                $randChallengesKeyOfArray = array_rand($this->challenges,2); //cambiando il valore numerico posso scegliere più challenge
                $randChallengesId = array();
                foreach ($randChallengesKeyOfArray as $key) {
                    $randChallengesId[] = $this->challenges[$key]->id;
                }
                $this->progress = $this->progressService->assign($this->userId,$randChallengesId);
                OW::getFeedback()->info(OW::getLanguage()->text('spodtutorial','new_challenge'));
            }
            elseif ((time()-strtotime($this->progress->timestamp)) > 82800 ) { //in questo caso aggiorno le daily challenge
                //prendo id delle challenge superate dall'utente
                $passed = json_decode($this->progress->passedChallengesId);

                //prendo id e poi le challenge corrispondenti rimanenti per l'utente
                $remained = array_diff($this->challengesId,$passed);
                $remainedChallenges = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findByIdList($remained);
                $temp = $this->util->checkDependencies($remainedChallenges,$passed);

                $randChallengesKeyOfArray = array_rand($remainedChallenges!=null?$remainedChallenges:$this->challenges,2); //cambiando il valore numerico posso scegliere più challenge
                $randChallengesId = array();
                foreach ($randChallengesKeyOfArray as $key) {
                    $randChallengesId[] = $this->challenges[$key]->id;
                }
                $this->progress = $this->progressService->assign($this->userId,$randChallengesId);
                OW::getFeedback()->info(OW::getLanguage()->text('spodtutorial','new_challenge'));
            }
        }

        SPODTUTORIAL_CLASS_Checker::getInstance()->checkDatalets($this->userId);

        $this->progress = $this->progressService->findByUserId($this->userId);
        $this->progressbar = count(json_decode($this->progress->passedChallengesId));

        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('spodtutorial')->getStaticJsUrl() . 'challenge.js', 'text/javascript');
        $script = UTIL_JsGenerator::composeJsString('
                SPODTUTORIAL.ajax_update_progress = {$ajax_update_progress}
            ', array(
            'ajax_update_progress' => OW::getRouter()->urlFor('SPODTUTORIAL_CTRL_AjaxChallenge', 'updateProgress')
        ));
        OW::getDocument()->addOnloadScript($script);

        $js = "
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

    public function onBeforeRender()
    {
        $this->assign('components_url', SPOD_COMPONENTS_URL);
        $this->assign('value',$this->progressbar);
        $this->assign('count',count($this->challenges));
        $this->assign('flag',true);

        if($this->userId == OW::getUser()->getId()) {
            $this->assign('challengesId',json_decode($this->progress->assignedChallengesId));
        }
        else{
            $this->assign('flag',false);
        }
    }
}