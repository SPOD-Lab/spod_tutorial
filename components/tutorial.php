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
    private $progressbar;

    public function __construct(BASE_CLASS_WidgetParameter $paramObject)
    {
        parent::__construct();
        $this->challenges = SPODTUTORIAL_BOL_ChallengeDao::getInstance()->findAll();

        $this->progressService = SPODTUTORIAL_BOL_ProgressService::getInstance();
        $this->userId =  $paramObject->additionalParamList['entityId'] != null ? $paramObject->additionalParamList['entityId'] : OW::getUser()->getId();

        if($this->userId == OW::getUser()->getId()) {
            $this->progress = $this->progressService->findByUserId($this->userId);
            //var_dump($this->progress);die();

            if($this->progress == null || (time()-strtotime($this->progress->timestamp)) > 82800 ) {
                $randChallengesIdInArray = array_rand($this->challenges,2);
                $randChallengesId = array(
                    $this->challenges[$randChallengesIdInArray[0]]->id,
                    $this->challenges[$randChallengesIdInArray[1]]->id
                );
                $this->progressService->assign($this->userId,$randChallengesId);
                $this->progress = $this->progressService->findByUserId($this->userId);
            }
        }

        $this->progressbar = count($this->progress->passedChallengesId);

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
        $this->assign('value',$this->progressbar);
        $this->assign('count',count($this->challenges));
        $this->assign('flag',true);

        if($this->userId == OW::getUser()->getId()) {
            $this->assign('firstChallengeId',json_decode($this->progress->assignedChallengesId)[0]);
            $this->assign('secondChallengeId',json_decode($this->progress->assignedChallengesId)[1]);
        }
        else{
            $this->assign('flag',false);
        }
    }
}