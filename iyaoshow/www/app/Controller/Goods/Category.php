
<?php

class Controller_Goods_Category extends Controller_Base
{
    /**
     * 分类列表
     */
    public function indexAction()
    {
        $aData = Model_Category::getMenu();
        $this->assign('aTree',$aData);
    }

    //添加分类
    public function addAction()
    {
        $aParam['sName'] = $this->getParam('sName');
        $aParam['iParentID'] = intval($this->getParam('iParentID'));
        if ($this->isPost()) {
            $aParam['iCreateUser'] = $aParam['iUpdateUser'] = $this->aCurrUser['iUserID'];
            $aCategory = $this->_checkData($aParam);
            if (empty($aCategory)) {
                return null;
            }
            $aData = Model_Category::exsistCategory($aParam['sName'],$aParam['iParentID']);
            if (!empty($aData)) {
                return $this->showMsg('已存在该分类', false);
            }
            if (Model_Category::addData($aCategory)) {
                return $this->showMsg('分类添加成功', true);
            }
        } else {
            $aData = Model_Category::getMenu();
            $this->assign('aTree',$aData);
        }
    }

    //编辑分类
    public function editAction()
    {
        $aParam['iID'] = intval($this->getParam('id'));
        $aParam['iID1'] = intval($this->getParam('iID'));
        $aParam['sName'] = $this->getParam('sName');
        $aParam['iParentID'] = intval($this->getParam('iParentID'));
        if ($this->isPost()) {
            if ($aParam['iID'] != $aParam['iID1']) {
                $this->showMsg('非法操作',false);
            }
            unset($aParam['iID1']);
            $aParam['iUpdateUser'] = $this->aCurrUser['iUserID'];
            $aCategory = $this->_checkData($aParam);
            if (empty($aCategory)) {
                return null;
            }
            $aData = Model_Category::exsistCategory($aParam['sName'],$aParam['iParentID']);
            if (!empty($aData)) {
                return $this->showMsg('已存在该分类', false);
            }
            if (Model_Category::updData($aCategory)) {
                return $this->showMsg('分类修改成功', true);
            }
        } else {
            $aCategory = Model_Category::getDetail($aParam['iID']);
            if (empty($aCategory)) {
                $this->showMsg('该分类不存在',false);
            }
            $aData = Model_Category::getMenu();
            $this->assign('aCategory',$aCategory);
            $this->assign('aTree',$aData);
        }
    }

    //删除分类
    public function delAction ()
    {
        $iCategoryID = intval($this->getParam('id'));
        $iRet = Model_Category::delData($iCategoryID);
        if ($iRet == 1) {
            return $this->showMsg('菜单删除成功！', true);
        } else {
            return $this->showMsg('菜单删除失败！', false);
        }
    }

    public function actionAfter()
    {
        parent::actionAfter();
        $this->_assignUrl();
    }

    protected function _assignUrl()
    {
        $this->assign('sListUrl', '/goods/category/');
        $this->assign('sAddUrl', '/goods/category/add/');
        $this->assign('sEditUrl', '/goods/category/edit/');
        $this->assign('sDelUrl', '/goods/category/del/');
        $this->assign('sPublishUrl', '/goods/category/publish/');
        $this->assign('sOffUrl', '/goods/category/off/');
    }
}