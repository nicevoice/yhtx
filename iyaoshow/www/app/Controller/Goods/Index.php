<?php

class Controller_Goods_Index extends Controller_Base
{
    /**
     * 商品列表
     */
    public function indexAction()
    {
        $aParam = $this->_getParams(0);
        print_r($aParam);die;

        $iPage = intval($this->getParam('page'));
        $sOrder = !empty($aParam['sOrder']) ? $aParam['sOrder'] : 'iUpdateTime DESC';
        $aParam['sOrder'] = $sOrder;
        $aList = Model_Goods::getPageList($aParam, $iPage, $sOrder);
        $aParam['iSTime'] = strtotime($aParam['iSTime']);
        $aParam['iETime'] = strtotime($aParam['iETime']);
        $this->assign('aParam', $aParam);
        $this->assign('aList', $aList);
        $aTree = Model_Category::getMenu();
        $this->assign('aTree',$aTree);
        $this->assign('aUnlockType',$this->aUnlockType);
        $this->assign('aUnlockLevel',$this->aUnlockLevel);
    }

    /**
     * 增加商品
     *
     * @return boolean
     */
    public function addAction()
    {
        if ($this->isPost()) {
            $aData = $this->_checkData(1);
            if (empty($aData)) {
                return null;
            }
            $aData['iPublishStatus'] = 0;//发布需要将该字段改为1
            //增加需要加上当前添加人ID
            $aCurrUserInfo = $this->aCurrUser;
            $aData['iUpdateUserID'] = $aData['iCreateUserID'] = $aCurrUserInfo['iUserID'];
            $iGoodsID = Model_Goods::addData($aData);

            if ($iGoodsID > 0) {
                return $this->showMsg('商品添加成功！', true);
            } else {
                return $this->showMsg('商品添加失败！', false);
            }
        } else {
            $this->_response->setHeader('Access-Control-Allow-Origin', '*.*');
            $aTree = Model_Category::getMenu();
            $this->assign('aTree',$aTree);
            $this->assign('aUnlockType',$this->aUnlockType);
            $this->assign('aUnlockLevel',$this->aUnlockLevel);


            $this->assign('sUploadUrl', Yaf_G::getConf('upload', 'url'));
            $this->assign('sFileBaseUrl', 'http://' . Yaf_G::getConf('file', 'domain'));
        }
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
     * 接收param方法
     * @param $iType 0:搜索,1:添加,2:编辑
     * @return mixed
     */
    private function _getParams($iType)
    {
        if ($iType == 0 || $iType == 2) {
            $aParam['iID'] = intval($this->getParam('iID'));//id
        }
        if ($iType == 0) {
            $aParam['iCostsStart'] = intval($this->getParam('iCostsStart'));//成本开始价
            $aParam['iCostsEnd'] = intval($this->getParam('iCostsEnd'));//成本结束价
            $aParam['iPriceStart'] = intval($this->getParam('iPriceStart'));//起始价格
            $aParam['iPriceEnd'] = intval($this->getParam('iPriceEnd'));//结束价格
            $aParam['iSTime'] = intval($this->getParam('iSTime'));//发布开始时间
            $aParam['iETime'] = intval($this->getParam('iETime'));//发布结束时间
            $aParam['iAgentRateStart'] = intval($this->getParam('iAgentRateStart'));//代理商提成开始比例
            $aParam['iAgentRateEnd'] = intval($this->getParam('iAgentRateEnd'));//代理商提成结束比例
            $aParam['iUnlockPointStart'] = intval($this->getParam('iUnlockPointStart'));//解锁所需解锁点
            $aParam['iUnlockPointEnd'] = intval($this->getParam('iUnlockPointEnd'));//解锁所需解锁点
            $aParam['iPublishStatus'] = intval($this->getParam('iPublishStatus'));//发布状态
        }
        if ($iType == 1 || $iType == 2 ) {
            $aParam['iCosts'] = intval($this->getParam('iCosts'));//成本价
            $aParam['iPrice'] = intval($this->getParam('iPrice'));//价格
            $aParam['iUnlockType'] = intval($this->getParam('iUnlockType'));//解锁类型
            $aParam['iAgentRate'] = intval($this->getParam('iAgentRate'));//代理商提成比例
            $aParam['iUnlockLevel'] = intval($this->getParam('iUnlockLevel'));//解锁等级
            $aParam['iUnlockPoint'] = intval($this->getParam('iUnlockPoint'));//解锁所需解锁点
            $aParam['sThumbImg'] = $this->getParam('sImage');//缩略图
            $aParam['sContent'] = htmlspecialchars($this->getParam('editorValue'));//内容
        }
        $aParam['sName'] = $this->getParam('sName');//产品名称
        $aParam['sDesc'] = $this->getParam('sDesc');//产品描述
        $aParam['iCatID'] = intval($this->getParam('iCatID'));//种类ID
        $aParam['iIsHot'] = intval($this->getParam('iIsHot'));//是否热门
        $aParam['iIsRecommend'] = intval($this->getParam('iIsRecommend'));//是否推荐
        return $aParam;
    }


    /**
     * 请求数据检测
     * @param int $iType
     * @param array $param
     * @return array|bool
     */
    public function _checkData($iType,$param = array())
    {
        $aRow = empty($param) ? $this->_getParams($iType) : $param;
        if (!Util_Validate::isCLength($aRow['sName'], 2, 20)) {
            return $this->showMsg('商品标题长度范围为2到20个字！', false);
        }
        if (!Util_Validate::isCLength($aRow['sDesc'], 2, 20)) {
            return $this->showMsg('商品描述长度范围为2到200个字！', false);
        }
        if (!Util_Validate::isFloat($aRow['iCosts'])) {
            return $this->showMsg('商品成本必须是数字！', false);
        }
        if (!Util_Validate::isFloat($aRow['iPrice'])) {
            return $this->showMsg('商品价格必须是数字！', false);
        }
        if (!Util_Validate::isInt($aRow['iCatID'])) {
            return $this->showMsg('商品类别必须是数字！', false);
        }
        if (!Util_Validate::isInt($aRow['iAgentRate'])) {
            return $this->showMsg('代理商比例必须是数字！', false);
        }
        if ($aRow['iUnlockType'] == -1) {
            return $this->showMsg('请选择商品解锁类型！', false);
        }
        if ($aRow['iUnlockType'] == 0 && $aRow['iUnlockLevel'] <= 0) {
            return $this->showMsg('请选择商品解锁级别！', false);
        }
        if ($aRow['iUnlockType'] == 0 && $aRow['iUnlockPoint'] == 0) {
            return $this->showMsg('请选择商品解锁点！', false);
        }
        if ($aRow['sThumbImg'] == '') {
            return $this->showMsg('商品缩略图不能为空', false);
        }
        if ($aRow['iPrice'] < $aRow['iCosts']*2) {
            return $this->showMsg('价格必须为成本的2倍以上', false);
        }
        if ($iType == 1) {
            $aGoods = Model_Goods::getGoodsByName($aRow['sName']);
            if ($aGoods['sName'] == $aRow['sName']) {
                return $this->showMsg('商品名称不能重复', false);
            }
        }
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
        $this->_assignUrl();
    }

    protected function _assignUrl()
    {
        $this->assign('sListUrl', '/goods/');
        $this->assign('sAddUrl', '/goods/add/');
        $this->assign('sEditUrl', '/goods/edit/');
        $this->assign('sDelUrl', '/goods/del/');
        $this->assign('sPublishUrl', '/goods/publish/');
        $this->assign('sOffUrl', '/goods/off/');
    }

}