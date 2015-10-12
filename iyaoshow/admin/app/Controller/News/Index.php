<?php

class Controller_News_Index extends Controller_Base
{
    /**
     * 资讯列表
     */
    public function indexAction()
    {
        $aParam = array();
        $aParam['iNewsID'] = $this->getParam('iNewsID');
        $aParam['iCategoryID'] = $this->getParam('iCategoryID');
        $aParam['iPublishStatus'] = $this->getParam('iPublishStatus');
        $aParam['sTitle'] = $this->getParam('sTitle');
        $aParam['sAuthor'] = $this->getParam('sAuthor');
        $aParam['sOrder'] = $this->getParam('sOrder');
        $aParam['iSTime'] = $this->getParam('iSTime');
        $aParam['iETime'] = $this->getParam('iETime');
        $aParam['sLoupanName'] = $this->getParam('sLoupanName');
        $aParam['sTagName'] = $this->getParam('sTagName');
        $aParam['iTagID'] = $this->getParam('iTagID') && !empty($aParam['sTagName']) ? $this->getParam('iTagID') : 0;
        $aParam['iAuthorID'] = $this->getParam('iAuthorID') && !empty($aParam['sAuthor']) ? $this->getParam('iAuthorID') : 0;
        $aParam['iLoupanID'] = $this->getParam('iLoupanID') && !empty($aParam['sLoupanName']) ? $this->getParam('iLoupanID') : 0;
        $aParam['iCityID'] = $this->_getCityID();
        $aParam['iTypeID'] = $this->_getTypeID();

        $iPage = intval($this->getParam('page'));
        $sOrder = 'iUpdateTime DESC';
        if (!empty($aParam['sOrder'])) {
            $sOrder = $aParam['sOrder'];
        }
        $aParam['sOrder'] = $sOrder;
        $aList = Model_News::getPageList($aParam, $iPage, $sOrder);
        $aParam['iSTime'] = strtotime($aParam['iSTime']);
        $aParam['iETime'] = strtotime($aParam['iETime']);
        $this->assign('iTypeID', $this->_getTypeID());
        $this->assign('iCityID', $this->_getCityID());
        $this->assign('aParam', $aParam);
        $this->assign('aList', $aList);
        $this->assign('aCityCode',
            Model_City::getPair(array('where' => array('iCityID' => $this->_getCityID(), 'iShow' => 1, 'iStatus' => 1)), 'iCityID', 'sFullPinyin'));
        $aCategory = Model_Category::getPairCategorys($this->_getTypeCategory());
        $this->assign('aCategory', $aCategory);

    }

    /**
     * 下架资讯
     */
    public function offAction()
    {
        $iNewsID = $this->getParam('id');
        if (!$iNewsID) {
            $this->showMsg('非法操作', false);
        }
        if (is_string($iNewsID) && strpos($iNewsID, ",") === false) {
            $iNewsID = array($iNewsID);
        } elseif (is_string($iNewsID) && strpos($iNewsID, ",")) {
            $iNewsID = explode(",", $iNewsID);
        } else {
            return $this->showMsg('非法操作！', false);
        }
        $fail_news = array();
        $secc_news = array();
        foreach ($iNewsID as $key => $value) {
            $aNews[$key] = Model_News::getDetail($value);
            $aRow = array(
                'iNewsID' => $value,
                'iPublishStatus' => 0
            );
            $iRet = Model_News::updData($aRow);
            if ($iRet != 1) {
                $fail_news[] = $value;
            } else {
                $secc_news[] = $value;
            }
        }
        $return = array("fail" => $fail_news, "secc" => $secc_news);
        $this->getResponse()->clearBody();
        return $this->showMsg($return, true);
    }

    /**
     * 发布资讯
     */
    public function publishAction()
    {
        $iNewsID = $this->getParam('id');
        if (!$iNewsID) {
            $this->showMsg('非法操作', false);
        }
        if (is_string($iNewsID) && strpos($iNewsID, ",") === false) {
            $iNewsID = array($iNewsID);
        } elseif (is_string($iNewsID) && strpos($iNewsID, ",")) {
            $iNewsID = explode(",", $iNewsID);
        } else {
            return $this->showMsg('非法操作！', false);
        }
        $fail_news = array();
        $secc_news = array();
        foreach ($iNewsID as $key => $value) {
            $aNews[$key] = Model_News::getDetail($value);
            $aRow = $this->_checkData('edit', $aNews[$key]);
            if (empty($aRow)) {
                $fail_news[] = $value;
                continue;
            }
            $aRow = array(
                'iNewsID' => $value,
                'iPublishStatus' => 1
            );
            $iRet = Model_News::updData($aRow);
            if ($iRet != 1) {
                $fail_news[] = $value;
            } else {
                $secc_news[] = $value;
            }

        }
        $return = array("fail" => $fail_news, "secc" => $secc_news);
        $this->getResponse()->clearBody();
        return $this->showMsg($return, true);
    }

    /**
     * 删除资讯
     *
     * @return boolean
     */
    public function delAction()
    {
        $iNewsID = $this->getParam('id');
        if (!$iNewsID) {
            return $this->showMsg('非法操作！', false);
        }
        if (is_string($iNewsID) && strpos($iNewsID, ",") === false) {
            $iNewsID = array($iNewsID);
        } elseif (is_string($iNewsID) && strpos($iNewsID, ",")) {
            $iNewsID = explode(",", $iNewsID);
        } else {
            return $this->showMsg('非法操作！', false);
        }
        $fail_news = array();
        foreach ($iNewsID as $key => $value) {
            $iRet = Model_News::delData($value);
            if ($iRet != 1) {
                $fail_news[] = $value;
            }
        }
        if (empty($fail_news)) {
            return $this->showMsg('资讯删除成功！', true);
        }
        $ids = join(',', $fail_news);
        return $this->showMsg('资讯' . $ids . '删除失败！', false);
    }

    /**
     * 编辑资讯
     *
     * @return boolean
     */
    public function editAction()
    {
        if ($this->isPost()) {
            $aNews = $this->_checkData('edit');
            if (empty($aNews)) {
                return null;
            }
            $sAction = '保存';
            if ($this->getParam('iOptype') > 0) {
                $aNews['iPublishStatus'] = 1;//发布需要将该字段改为1
                $sAction = '发布';
            }
            $aNews['iNewsID'] = intval($this->getParam('iNewsID'));
            //修改需要加上当前修改人ID
            $aCurrUserInfo = $this->aCurrUser;
            $aNews['iUpdateUserID'] = $aCurrUserInfo['iUserID'];
            if (1 == Model_News::updData($aNews)) {
                return $this->showMsg(['sMsg' => '资讯信息' . $sAction . '成功！', 'iNewsID' => $aNews['iNewsID']], true);
            } else {
                return $this->showMsg('资讯信息' . $sAction . '失败！', false);
            }
        } else {
            $this->_response->setHeader('Access-Control-Allow-Origin', '*');

            $iNewsID = intval($this->getParam('id'));
            $aNews = Model_News::getDetail($iNewsID);
            /**
            Model_News::changeNewsLouName($aNews);
            print_r($aNews);
            die
             */;
            if ($aNews['sTag']) {
                $aNews['aTag'] = explode(',', $aNews['sTag']);
            }

            if ($aNews['iAuthorID'] == 0) {
                //处理老数据的作者问题
                $aAuthor = Model_Author::getAll(
                    [
                        'where' => [
                            'sAuthorName' => $aNews['sAuthor']
                        ]
                    ]
                );

                if ($aAuthor) {
                    foreach ($aAuthor as $author) {
                        $aNews['iAuthorID'] = $author['iAuthorID'];
                        if ($author['iCityID'] == $aNews['iCityID']) {
                            break;
                        }
                    }
                }
            }
            $this->assign('aNews', $aNews);

            $aCategory = Model_Category::getPairCategorys($this->_getTypeCategory());
            $aTag = $this->_getTagList();//Model_Tag::getPairTags($this->_getTypeTag());
            $aLoupan = Model_CricUnit::getLoupanNames($aNews['sLoupanID']);
            $this->assign('iTypeID', $this->_getTypeID());
            $this->assign('iCityID', $this->_getCityID());
            $this->assign('aCategory', $aCategory);
            $this->assign('aTag', $aTag);
            $this->assign('aLoupan', $aLoupan);
            $this->assign('sUploadUrl', Yaf_G::getConf('upload', 'url'));
            $this->assign('sFileBaseUrl', 'http://' . Yaf_G::getConf('file', 'domain'));
        }
    }

    /**
     * 增加资讯
     *
     * @return boolean
     */
    public function addAction()
    {
        if ($this->isPost()) {
            $aNews = $this->_checkData();
            if (empty($aNews)) {
                return null;
            }
            $sAction = '保存';
            if ($this->getParam('iOptype') > 0) {
                $aNews['iPublishStatus'] = 1;//发布需要将该字段改为1
                $sAction = '发布';
            }
            //增加需要加上当前添加人ID
            $aCurrUserInfo = $this->aCurrUser;
            $aNews['iUpdateUserID'] = $aCurrUserInfo['iUserID'];
            $aNews['iCreateUserID'] = $aCurrUserInfo['iUserID'];
            $iNewsID = Model_News::addData($aNews);

            if ($iNewsID > 0) {
                return $this->showMsg(['sMsg' => '资讯信息' . $sAction . '成功！', 'iNewsID' => $iNewsID], true);
            } else {
                return $this->showMsg('资讯信息' . $sAction . '失败！', false);
            }
        } else {
            $this->_response->setHeader('Access-Control-Allow-Origin', '*.*');
            $aCategory = Model_Category::getPairCategorys($this->_getTypeCategory());
            $aTag = $this->_getTagList();//Model_Tag::getPairTags($this->_getTypeTag());
            $this->assign('iTypeID', $this->_getTypeID());
            $this->assign('iCityID', $this->_getCityID());
            $this->assign('aCategory', $aCategory);
            $this->assign('aTag', $aTag);
            $this->assign('sUploadUrl', Yaf_G::getConf('upload', 'url'));
            $this->assign('sFileBaseUrl', 'http://' . Yaf_G::getConf('file', 'domain'));
        }
    }

    /**
     * 接收param方法
     * param $newid 0:接收数据,大于0:表中获取数据,通过传进来的newsid获取
     * return array
     */
    private function _getParams()
    {
        return $aRow = array(
            'sTitle' => trim($this->getParam('sTitle')),
            'iCityID' => $this->_getCityID(),
            'iCategoryID' => intval($this->getParam('iCategoryID')),
            'sAuthor' => $this->getParam('sAuthor'),
            'iAuthorID' => $this->getParam('iAuthorID'),
            'sAbstract' => $this->getParam('sAbstract'),
            'sContent' => $this->getParam('sContent'),
            'sLoupanID' => trim($this->getParam('sLoupanID')),
            'sSource' => $this->getParam('sSource'),
            'sTag' => trim($this->getParam('sTag')),
            'sKeyword' => $this->getParam('sKeyword'),
            'sImage' => $this->getParam('sImage'),
            'sShortTitle' => $this->getParam('sShortTitle'),
            'iPublishTime' => strtotime($this->getParam('iPublishTime')),
            'iTypeID' => $this->_getTypeID(),
            'iOptype' => $this->getParam('iOptype') ? $this->getParam('iOptype') : 0,//操作类型0：保存,1：发布
            'sMedia' => trim($this->getParam('sMedia'))
        );
    }


    /**
     * 请求数据检测
     * @param $sType 操作类型 add:添加,edit:修改:
     * @param $iOptype 操作类型 0:保存,1:发布
     * @return mixed
     *
     */
    public function _checkData($sType = 'add', $param = array())
    {
        $aRow = empty($param) ? $this->_getParams() : $param;
        //保存和发布都需要做的判断
        if (!Util_Validate::isCLength($aRow['sTitle'], 5, 22)) {
            return $this->showMsg('资讯标题长度范围为5到22个字！', false);
        }
        if (!empty($param) || $aRow['iOptype'] > 0) {
            if (!Util_Validate::isCLength($aRow['sShortTitle'], 5, 15)) {
                return $this->showMsg('短标题长度范围为5到15个字！', false);
            }
            if (!Util_Validate::isCLength($aRow['sAuthor'], 2, 20)) {
                return $this->showMsg('资讯作者长度范围为2到20个字！', false);
            }
            if (!Util_Validate::isCLength($aRow['sMedia'], 1, 20)) {
                return $this->showMsg('媒体来源长度范围为1到20个字！', false);
            }
            if (!Util_Validate::isCLength($aRow['sKeyword'], 2, 50)) {
                return $this->showMsg('关键字长度范围为2到20个字！', false);
            }
            if (empty($aRow['sImage'])) {
                return $this->showMsg('请选择一张默认图片！', false);
            }
            if (!Util_Validate::isCLength($aRow['sAbstract'], 60, 90)) {
                return $this->showMsg('资讯摘要长度范围为60到90个字！', false);
            }
            if (!Util_Validate::isCLength($aRow['sContent'], 100, 16777215)) {
                return $this->showMsg('资讯内容长度范围为100到65535个字！', false);
            }
            if ($aRow['iCategoryID'] < 0) {
                return $this->showMsg('请选择一个资讯分类！', false);
            }
            if ($aRow['iPublishTime'] == 0) {
                $iPublishTime = time();
            }
            if (!Model_Author::getAuthorByName($aRow['sAuthor'])) {
                return $this->showMsg('作者不存在', false);
            }
            /**
            if (Model_News::EVALUATION_NEWS == $this->_getTypeID()) {
                if (empty($aRow['sLoupanID'])) {
                    return $this->showMsg('请添加推送楼盘', false);
                }
            }*/
            if ($aRow['sLoupanID']) {
                $aLoupanID = explode(',', $aRow['sLoupanID']);
                foreach ($aLoupanID as $key => $value) {
                    if (!Model_CricUnit::getLoupanByID($value)) {
                        unset($aLoupanID[$key]);
                        $iLouChange = 1;//楼盘ID过滤标记
                        //return $this->showMsg('推送楼盘不存在', false);
                    }
                }
                if (isset($iLouChange)) {
                    $aRow['sLoupanID'] = implode(',',$aLoupanID);
                }
            }

            if ($aRow['sTag']) {
                $sTag = explode(',', $aRow['sTag']);
                foreach ($sTag as $key => $value) {
                    $aTag = Model_Tag::getDetail($value);
                    if (empty($aTag) || $aTag['iStatus'] != 1 || $aTag['iTypeID'] != $this->_getTypeTag()) {
                        return $this->showMsg('资讯标签不存在,无效标签名称为（' . $value . ')', false);
                    }
                }
            }
        }
        //去掉非字段的元素
        unset($aRow['iOptype']);
        return $aRow;
    }

    protected function _getCityID()
    {
        return $this->iCurrCityID;
    }

    protected function _getTypeID()
    {
        return Model_News::GUIDE_NEWS;
    }

    protected function _getTypeCategory()
    {
        return Model_Category::CATEGORY_GUIDE_NEWS;
    }

    protected function _getTypeTag()
    {
        return Model_Tag::TAG_GUIDE_NEWS;
    }

    protected function _getTagList()
    {
        $iTypeID = $this->_getTypeID();
        $iCityID = $this->_getCityID();
        if ($iCityID > 0) {
            $aCityID = [$iCityID, 0];
        } else {
            $aCityID = $iCityID;
        }
        return Model_Tag::getNewsTag($aCityID, $iTypeID);
    }

    public function actionAfter()
    {
        parent::actionAfter();
        $aCurrentCity = Model_City::getCityByID($this->iCurrCityID);
        $this->assign('aCurrentCity', $aCurrentCity);
        $this->_assignUrl();
    }

    protected function _assignUrl()
    {
        $this->assign('sListUrl', '/news/');
        $this->assign('sAddUrl', '/news/add/');
        $this->assign('sEditUrl', '/news/edit/');
        $this->assign('sDelUrl', '/news/del/');
        $this->assign('sPublishUrl', '/news/publish/');
        $this->assign('sOffUrl', '/news/off/');
    }

}