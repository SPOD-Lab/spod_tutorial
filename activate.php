<?php
/**
 * Created by PhpStorm.
 * User: Dario
 * Date: 10/01/2017
 * Time: 11:29
 */

$widgetService = BOL_ComponentAdminService::getInstance();

$widget = $widgetService->addWidget('SPODTUTORIAL_CMP_Tutorial', true);
$widgetPlace = $widgetService->addWidgetToPlace($widget, BOL_ComponentService::PLACE_PROFILE);
$widgetService->addWidgetToPosition($widgetPlace, BOL_ComponentService::SECTION_LEFT, 1);

$widgetPlace = $widgetService->addWidgetToPlace($widget, BOL_ComponentService::PLACE_INDEX);
$widgetService->addWidgetToPosition($widgetPlace, BOL_ComponentService::SECTION_LEFT);