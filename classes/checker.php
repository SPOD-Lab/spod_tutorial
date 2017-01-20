<?php

/**
 * Created by PhpStorm.
 * User: darcas
 * Date: 19/01/2017
 * Time: 14:20
 */
class SPODTUTORIAL_CLASS_Checker {

    private static $classInstance;

    public static function getInstance()
    {
        if(self::$classInstance === null)
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    protected function __construct() {

    }

    public function checkDatalets($userId) {
        $example = new OW_Example();
        $example->andFieldEqual('ownerId',$userId);

        $list = ODE_BOL_DataletDao::getInstance()->findListByExample($example);
        if(count($list)>=1) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,1);
        }
        if($this->checkIfDataletHasFilters($list)) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,2);
        }
        if($this->checkIfDataletHasGroup($list)) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,3);
        }
        if($this->checkIfDataletHasGroup($list) && $this->checkIfDataletHasFilters($list)) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,4);
        }
        if($this->checkDatatable($list)) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,5);
        }
        if($this->checkBarchart($list)) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,6);
        }
        if($this->checkMap($list)) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,7);
        }
    }

    public function checkLink($userId) {

        $example = new OW_Example();
        $example->andFieldEqual('ownerId',$userId);
        $example->andFieldEqual('cardType','link');

        $list = SPODPR_BOL_PrivateRoomDao::getInstance()->findListByExample($example);
        if(count($list)>=1) {
            SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,11);
        }
    }

    protected function checkDatatable($list) {
        foreach($list as $datalet) {
            if($datalet->component == 'datatable-datalet') {
                return true;
            }
        }
        return false;
    }

    protected function checkBarchart($list) {
        foreach($list as $datalet) {
            if($datalet->component == 'barchart-datalet') {
                return true;
            }
        }
        return false;
    }

    protected function checkMap($list) {
        foreach($list as $datalet) {
            if($datalet->component == 'leafletjs-datalet') {
                return true;
            }
        }
        return false;
    }

    protected function checkIfDataletHasFilters($list) {
        foreach($list as $datalet) {
            $params = json_decode($datalet->params);
            if($params->{'filters'}!="[]") {
                return true;
            }
        }
        return false;
    }

    protected function checkIfDataletHasGroup($list) {
        foreach($list as $datalet) {
            $params = json_decode($datalet->params);
            if($params->{'aggregators'}!="[]") {
                return true;
            }
        }
        return false;
    }
}