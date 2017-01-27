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

    public function checkChallenges($userId) {
        $count = 0;
        $count += $this->checkDatalets($userId);
        $count += $this->checkLink($userId);
        $count += $this->checkAttachment($userId);
        $count += $this->checkText($userId);
        $count += $this->checkLike($userId);
        $count += $this->checkCocreation($userId);
        $count += $this->checkPost($userId);
        $count += $this->checkPublicRoom($userId);

        return $count;
    }

    /**
     * Challeneges checked by this method: 1 2 3 4 5 6 7 8 9
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkDatalets($userId) {
        $count = 0;
        $example = new OW_Example();
        $example->andFieldEqual('ownerId',$userId);

        $list = ODE_BOL_DataletDao::getInstance()->findListByExample($example);
        if(count($list)>=1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,1)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','new_datalet_title'));
            }
        }
        if($this->checkIfDataletHasFilters($list)) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,2)){
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial',' filter_title'));
            }
        }
        if($this->checkIfDataletHasGroup($list)) {
           if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,3)) {
               $count += 1;
               OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','group_title'));
           }
        }
        if($this->checkIfDataletHasGroup($list) && $this->checkIfDataletHasFilters($list)) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,4)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','groupfilter_title'));
            }
        }
        if($this->checkDatatable($list)) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,5)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','datatable_title'));
            }
        }
        if($this->checkBarchart($list)) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,6)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','barchart_title'));
            }
        }
        if($this->checkMap($list)) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,7)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','map_title'));
            }
        }

        $example->andFieldEqual('cardType','datalet');
        $privateDatalets = SPODPR_BOL_PrivateRoomDao::getInstance()->findListByExample($example);
        foreach ($privateDatalets as $privateDatalet) {
            $card = json_decode($privateDatalet->card);
            $id = $card->{'dataletId'};
            $title = $card->{'title'};
            $comment = $card->{'comment'};
            foreach ($list as $datalet) {
                $params = json_decode($datalet->params);
                if($datalet->id != $id && $params->{'title'}==$title && $params->{'description'}==$comment) {
                    if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,8)) {
                        $count += 1;
                        OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','copy_title'));
                    }
                    if((strtotime($datalet->timestamp)-strtotime($privateDatalet->timestamp)) > 0) {
                        if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,9)) {
                            $count += 1;
                            OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','reuse_title'));
                        }
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Challeneges checked by this method: 10
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkPublicRoom($userId) {
        $count = 0;
        $publicRooms = SPODPUBLIC_BOL_Service::getInstance()->getPublicRoomsByOwner($userId);
        if(count($publicRooms)>=1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,10)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','agora_title'));
            }
        }

        return $count;
    }

    /**
     * Challeneges checked by this method: 11 12
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkLink($userId) {
        $count = 0;
        $example = new OW_Example();
        $example->andFieldEqual('ownerId',$userId);
        $example->andFieldEqual('cardType','link');

        $list = SPODPR_BOL_PrivateRoomDao::getInstance()->findListByExample($example);
        if(count($list)>=1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,11)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','new_link_title'));
            }
        }

        $dataletPosts = ODE_BOL_DataletPostDao::getInstance()->findAll();
        foreach ($dataletPosts as $dataletPost) {
            $datalet = ODE_BOL_Service::getInstance()->getDataletById($dataletPost->dataletId);
            if($datalet->ownerId == $userId) {
                $params = json_decode($datalet->params);
                if($params->url) {
                    if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,12)) {
                        $count += 1;
                        OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','reuse_link_title'));
                    }
                }
            }
        }
        return $count;
    }

    /**
     * Challeneges checked by this method: 13
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkAttachment($userId) {
        $count = 0;
        $example = new OW_Example();
        $example->andFieldEqual('userId',$userId);
        $list = BOL_AttachmentDao::getInstance()->findListByExample($example);
        if(count($list)>=1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,13)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','attach_title'));
            }
        }

        return $count;
    }

    /**
     * Challeneges checked by this method: 14
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkText($userId) {
        $count = 0;
        $example = new OW_Example();
        $example->andFieldEqual('ownerId',$userId);
        $example->andFieldEqual('cardType','text');

        $list = SPODPR_BOL_PrivateRoomDao::getInstance()->findListByExample($example);
        if(count($list)>=1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,14)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','new_text_title'));
            }
        }
        return $count;
    }

    /**
     * Challeneges checked by this method: 15 16
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkCocreation($userId) {
        $count = 0;
        $example = new OW_Example();
        $example->andFieldEqual('ownerId',$userId);
        $list = COCREATION_BOL_RoomDao::getInstance()->findListByExample($example);
        if(count($list)>=1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,15)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','cocreation_title'));
            }
        }

        $cocreationService = COCREATION_BOL_Service::getInstance();
        $rooms = $cocreationService->getAllRooms();
        foreach ($rooms as $room) {
            if($cocreationService->isMemberJoinedToRoom($userId,$room->id) || $room->ownerId == $userId) {
                $datasets = COCREATION_BOL_Service::getInstance()->getDatasetsByRoomId($room->id);
                if(count($datasets) >= 1) {
                    if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,16)) {
                        $count += 1;
                        OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','publish_title'));
                    }
                    break;
                }
                $example = new OW_Example();
                $example->andFieldEqual('roomId',$room->id);
                $datasets = COCREATION_BOL_DatasetDao::getInstance()->findListByExample($example);
                if(count($datasets) >= 1) {
                    if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,16)) {
                        $count += 1;
                        OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','publish_title'));
                    }
                    break;
                }
            }
        }
        return $count;
    }

    /**
     * Challeneges checked by this method: 17 19
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkPost($userId) {
        $count = 0;
        $example = new OW_Example();
        $example->andFieldEqual('userId',$userId);
        $comments = BOL_CommentDao::getInstance()->findListByExample($example);
        if(count($comments) >= 1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,17)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','post_title'));
            }
        }

        $status = NEWSFEED_BOL_Service::getInstance()->getStatus('user',$userId);
        if(count($status) >= 1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,17)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','post_title'));
            }
        }

        foreach ($comments as $comment) {
            $dataletsInPost = ODE_BOL_Service::getInstance()->getDataletByPostId($comment->id,'comment');
            if(count($dataletsInPost) >= 1) {
                if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,19)) {
                    $count += 1;
                    OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','comment_title'));
                }
            }
        }

        return $count;
    }

    /**
     * Challeneges checked by this method: 18
     * @param $userId int the id of the user we want to check
     * @return $count int number of challenges passed
     */
    protected function checkLike($userId) {
        $count = 0;
        if(count(NEWSFEED_BOL_Service::getInstance()->findUserLikes($userId))>=1) {
            if(SPODTUTORIAL_BOL_ProgressService::getInstance()->pass($userId,18)) {
                $count += 1;
                OW_Feedback::getInstance()->info('Challenge completed: '.OW_Language::getInstance()->text('spodtutorial','like_title'));
            }
        }
        return $count;
    }

    private function checkDatatable($list) {
        foreach($list as $datalet) {
            if($datalet->component == 'datatable-datalet') {
                return true;
            }
        }
        return false;
    }

    private function checkBarchart($list) {
        foreach($list as $datalet) {
            if($datalet->component == 'barchart-datalet') {
                return true;
            }
        }
        return false;
    }

    private function checkMap($list) {
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