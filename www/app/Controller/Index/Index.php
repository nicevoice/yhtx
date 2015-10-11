<?php

class Controller_Index_Index extends Controller_Base
{
    protected $bCheckLogin = false;

    public function indexAction ()
    {
        echo $_SERVER['REQUEST_URI'];die;
        //echo decbin(pow(10,100));die;
        //$aMenus = Model_Menu::getMenus();
        //$this->assign('aData',$aMenus);
        $this->_frame = 'frame.phtml';
        //return $this->redirect($sUrl);
    }

    public function welcomeAction()
    {

    }

    public function loginAction ()
    {

    }

    public function logoutAction ()
    {
        Util_Cookie::delete(Yaf_G::getConf('authkey', 'cookie'));
        $this->redirect('/login');
    }

    public function signinAction ()
    {
        $sUrl = '';
        return $this->showMsg(['msg' => '登录成功！', 'sUrl' => $sUrl], true);
    }

}