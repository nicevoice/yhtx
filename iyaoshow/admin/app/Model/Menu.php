<?php

/**
 *
 * @author xiejinci
 *
 */
class Model_Menu extends Model_Base
{

    const DB_NAME = 'yhtx';

    const TABLE_NAME = 'admin_menu';

    //按需求拼装数据结构
    public static function getMenus($iuserID=0)
    {
        $aData = self::getTree($iuserID);
        //获取一级带单列表
        $aSonsData = [];
        $i = 0;
        foreach ($aData as $key => $value) {
            $aTmp = [];
            $aTmp['id'] = $value['sCode'];
            $aTmp['menu'] = [];
            if (isset($value['aSons'])) {
                $aSons = $value['aSons'];
                $aTmpDatas = [];
                foreach ($aSons as $k => $v) {
                    $aTmpDatas['text'] = $v['sMenuName'];
                    $aTmpDatas['items'] = [];
                    if (isset($v['aSons'])) {
                        $aLastSons = $v['aSons'];
                        foreach ($aLastSons as $ks => $vs) {
                            if ($i == 0) {
                                $aTmp['homePage'] = $vs['sCode'];
                                $i = 1;
                            }
                            $aTmpDatas['items'][$ks]['id'] = $vs['sCode'];
                            $aTmpDatas['items'][$ks]['text'] = $vs['sMenuName'];
                            $aTmpDatas['items'][$ks]['href'] = $vs['sMenuUrl'];
                        }
                    }
                    $aTmp['menu'][] = $aTmpDatas;
                }
                unset($aData[$key]['aSons']);
            }
            $aSonsData[] = $aTmp;
        }
        return [
            'parent'=>$aData,
            'sons'=>$aSonsData
        ];
    }

    //获取树状menu
    public static function getTree($iUserID = 0)
    {
        //获取库中所有有效数据
        $aWhere = array(
            'iStatus' => 1,
        );
        $aList = self::getAll(array(
            'where' => $aWhere,
            'order' => 'iMenuID ASC'
        ));
        //按照父ID分组
        $aParents = array();//一级菜单列表
        foreach ($aList as $key => $value) {
            if ($value['iParentID'] == 0) {
                $aParents[$value['iMenuID']] = $value;
                unset($aList[$key]);
            }
        }
        //整合二级菜单
        foreach ($aList as $key => $value) {
            if (isset($aParents[$value['iParentID']])) {
                $aParents[$value['iParentID']]['aSons'][$value['iMenuID']] = $value;
                unset($aList[$key]);
                //整合三级菜单
                foreach ($aList as $k => $v) {
                    if ($v['iParentID'] == $value['iMenuID']) {
                        $aParents[$value['iParentID']]['aSons'][$value['iMenuID']]['aSons'][] = $v;
                        unset($aList[$k]);
                    }
                }
            }
        }
        return $aParents;
    }
}