
<?php

/**
 *
 * @author xiejinci
 *
 */
class Model_Category extends Model_Base
{

    const DB_NAME = 'yhtx';

    const TABLE_NAME = 'goods_category';

    //获取所有数据(key为数组的key)
    public static function getAllTree($sKey = '')
    {
        //获取库中所有有效数据
        $aWhere = array(
            'iStatus' => 1,
        );
        $aList = self::getAll(array(
            'where' => $aWhere,
            'order' => 'iID ASC'
        ));
        if ($sKey) {
            $aTmp = [];
            foreach ($aList as $key => $value) {
                $aTmp[$value[$sKey]] = $value;
            }
            $aList = $aTmp;
        }
        return $aList;
    }

    public static function getMenu()
    {
        $aTree = self::getTree();
        $aList = array();
        self::_toList($aTree, $aList);
        return $aList;
    }

    //获取树状menu
    public static function getTree()
    {
        $aList =self::getAllTree();
        $aParent = array();
        //整理父menu
        foreach ($aList as $key => $value) {
            $aParent[$value['iParentID']][] = $value;
        }
        unset($aList);
        return self::_buildTree ($aParent, 0, 0, '');
    }

    private static function _buildTree ($aParent, $iParentID, $iLevel, $sPath)
    {
        $aTree = array();
        if (isset($aParent[$iParentID])) {
            foreach ($aParent[$iParentID] as $aMenu) {
                $aMenu['iLevel'] = $iLevel;
                $aMenu['sPath'] = $sPath;
                $aMenu['aChild'] = self::_buildTree($aParent, $aMenu['iID'], $iLevel + 1, $sPath . ' menup' . $aMenu['iID']);
                if (!empty($aMenu)) {
                    foreach ($aMenu['aChild'] as $v) {
                        if ($v['iCurr'] == 1) {
                            $aMenu['iCurr'] = 1;
                            break;
                        }
                    }
                }
                $aTree[] = $aMenu;
            }
        }
        return $aTree;
    }

    private static function _toList ($aTree, &$aList)
    {
        foreach ($aTree as $aMenu) {
            $aMenu['bIsLeaf'] = empty($aMenu['aChild']) ? 1 : 0;
            $aChild = $aMenu['aChild'];
            unset($aMenu['aChild']);
            $aList[] = $aMenu;
            if ($aMenu['bIsLeaf'] == 0) {
                self::_toList($aChild, $aList);
            }
        }
    }

    public static function exsistCategory($sName, $iParent)
    {
        $aWhere = array(
            'iStatus' => 1,
            'sName like' => $sName,
            'iParentID' => $iParent,
        );
        $aList = self::getRow(array(
            'where' => $aWhere,
        ));
        return $aList;
    }
}