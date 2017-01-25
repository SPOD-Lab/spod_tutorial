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

SPODTUTORIAL_BOL_ProgressService::getInstance()->initDb();