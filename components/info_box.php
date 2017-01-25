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

        $this->assign('title', OW::getLanguage()->text('spodtutorial',$challenge->title));
        $this->assign('body',OW::getLanguage()->text('spodtutorial',$challenge->body));

        $this->assign('components_url', SPODPR_COMPONENTS_URL);
        $this->assign('id',$id);
        $helperComponentName = '';
        switch ($id) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $helperComponentName = 'ODE_CMP_HelperWhatsNewEn';
                break;
            case 10:
                $helperComponentName = 'SPODPUBLIC_CMP_HelperAgoraEn';
                break;
            case 11:
            case 12:
            case 14:
                $helperComponentName = 'SPODPR_CMP_HelperMySpaceEn';
                break;
            case 15:
            case 16:
                $helperComponentName = 'COCREATION_CMP_HelperCocreationEn';
                break;
            case 13:
            case 17:
            case 18:
            case 19:
                $helperComponentName = '';
                break;
            default:
                break;
        }

        if($helperComponentName!='') {
            $js = "
SPODTUTORIAL.showHint = function()
{
    previewFloatBox.close()
    hintFloatBox = OW.ajaxFloatBox('".$helperComponentName."', {} , {width:'90%', height:'70vh', iconClass:'ow_ic_lens', title:''});
};
";
            OW::getDocument()->addOnloadScript($js);

            $js = '
        $("#close_button").click(function(){
            previewFloatBox.close()
        });
        ';
            OW::getDocument()->addOnloadScript($js);
        }
    }
}