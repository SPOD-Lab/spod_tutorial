<?php
/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 12/10/2016
 * Time: 13:29
 */
$preference = BOL_PreferenceService::getInstance()->findPreference('spodpr_components_url');
$spodpr_components_url = empty($preference) ? "http://deep.routetopa.eu/deep-components/" : $preference->defaultValue;
define("SPOD_COMPONENTS_URL", $spodpr_components_url);

if(OW::getPluginManager()->isPluginActive('ode') &&
    OW::getPluginManager()->isPluginActive('spodpublic') &&
    OW::getPluginManager()->isPluginActive('spodpr') &&
    OW::getPluginManager()->isPluginActive('cocreation') &&
    OW::getPluginManager()->isPluginActive('newsfeed')
){
    SPODTUTORIAL_BOL_ProgressService::getInstance()->initDb();
}
