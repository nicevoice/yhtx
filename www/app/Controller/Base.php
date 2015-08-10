<?php

class Controller_Base extends Yaf_Controller
{
    /**
     * 是否进行登录检测
     * @var unknown
     */
    protected $bCheckLogin = false;

    /**
     * 当前用户
     * @var unknown
     */
    protected $aCurrUser = null;

    /**
     * 当前项目
     * @var unknown
     */
    protected $aCurrProject = null;

    //解锁类型
    protected $aUnlockType =['级别解锁','解锁点解锁'];
    //解锁等级
    protected $aUnlockLevel =[1,2,3,4,5,6,7,8,9,10];

    /**
     * 执行Action前执行
     * @see Yaf_Controller::actionBefore()
     */
    public function actionBefore ()
    {
        $this->aCurrProject = Yaf_G::getConf('project');
        if ($this->bCheckLogin) {

        }
        $this->assign('sStaticRoot', 'http://' . Yaf_G::getConf('static', 'domain'));
        $this->assign('sRoot', 'http://' . Yaf_G::getConf('static', 'domain'));
        $this->assign('aMeta', array(
            'title' => $this->aCurrProject['name']
        ));

        //做个零时的
        $this->aCurrUser = [
            'iUsername'=>'admin',
            'iUserID'=> 1
        ];
    }

    /**
     * 执行Action后的操作
     * @see Yaf_Controller::actionAfter()
     */
    public function actionAfter ()
    {
        if ($this->autoRender() == true) {
            if (! empty($this->aCurrUser)) {

            }
            $aDebug = Util_Common::getDebugData();
            if ($aDebug) {
                $this->assign('__showDebugInfo__', 'showDebugInfo(' . json_encode($aDebug) . ');');
            }
        } else {}
    }

    /**
     * 取得是否要检查登录权限
     * @return boolean
     */
    public function getCheckLogin ()
    {
        return $this->bCheckLogin;
    }

    public function redirect ($url)
    {
        $response = $this->getResponse();
        $response->setRedirect($url);
        $this->autoRender(false);
        return false;
    }

    protected function _checkData($param = array())
    {
        $aRow = empty($param) ? $this->_getParams() : $param;
        if (!Util_Validate::isLength($aRow['sName'], 2, 20)) {
            return $this->showMsg('名称长度范围为2到20个字！', false);
        }
        return $aRow;
    }
}