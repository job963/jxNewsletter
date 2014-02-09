<?php

/*
 *    This file is part of the module jxNewsletter for OXID eShop Community Edition.
 *
 *    The module jxNewsletter for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module jxNewsletter for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxNewsletter
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2013-2014
 *
 */
 
class jxnewsletter_list extends oxAdminView
{
    protected $_sThisTemplate = "jxnewsletter_list.tpl";

    public function render()
    {
        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);

        $sChkAll = oxConfig::getParameter( 'jx_all' );
        $sChkConfirmed = oxConfig::getParameter( 'jx_confirmed' );
        $sChkUnconfirmed = oxConfig::getParameter( 'jx_unconfirmed' );
        $sChkUnsubscribed = oxConfig::getParameter( 'jx_unsubscribed' );
        $sChkBought = oxConfig::getParameter( 'jx_bought' );

        if (empty($sSrcVal))
            $sSrcVal = "";
        else
            $sSrcVal = strtoupper($sSrcVal);
        $oSmarty->assign( "jxnewsletter_srcval", $sSrcVal );

        $aUsers = $this->_retrieveData($sSrcVal);
        $oSmarty->assign("aUsers",$aUsers);

        $oSmarty->assign("jx_all",$sChkAll);
        $oSmarty->assign("jx_confirmed",$sChkConfirmed);
        $oSmarty->assign("jx_unconfirmed",$sChkUnconfirmed);
        $oSmarty->assign("jx_unsubscribed",$sChkUnsubscribed);
        $oSmarty->assign("jx_bought",$sChkBought);
        
        return $this->_sThisTemplate;
    }
    
    
    public function downloadResult()
    {
        
        $aUsers = array();
        $aUsers = $this->_retrieveData($sSrcVal);

        $aOxid = oxConfig::getParameter( "jxnewsletter_oxid" ); 
        
        $sContent = '';
        foreach ($aUsers as $aUser) {
            if ( in_array($aUser['oxid'], $aOxid) ) {
                $sContent .= '"' . implode('","', $aUser) . '"' . chr(13);
            }
        }

        header("Content-Type: text/plain");
        header("content-length: ".strlen($sContent));
        header("Content-Disposition: attachment; filename=\"newsletter-list.csv\"");
        echo $sContent;
        
        exit();

        return;
    }

    
    private function _retrieveData($sSrcVal)
    {
        
        $sChkAll = oxConfig::getParameter( 'jx_all' );
        $sChkConfirmed = oxConfig::getParameter( 'jx_confirmed' );
        $sChkUnconfirmed = oxConfig::getParameter( 'jx_unconfirmed' );
        $sChkUnsubscribed = oxConfig::getParameter( 'jx_unsubscribed' );
        $sChkBought = oxConfig::getParameter( 'jx_bought' );
        
        $sWhere = "";
        if ($sChkAll || $sChkConfirmed || $sChkConfirmed || $sChkUnconfirmed || $sChkBought) {
            if ($sChkAll) {
                if (!empty($sWhere)) $sWhere .= "OR ";
                $sWhere .= "n.oxdboptin=0 ";
            }
            if ($sChkConfirmed) {
                if (!empty($sWhere)) $sWhere .= "OR ";
                $sWhere .= "n.oxdboptin=1 ";
            }
            if ($sChkUnconfirmed) {
                if (!empty($sWhere)) $sWhere .= "OR ";
                $sWhere .= "n.oxdboptin=2 ";
            }
            if ($sChkUnsubscribed) {
                if (!empty($sWhere)) $sWhere .= "OR ";
                $sWhere .= "n.oxunsubscribed != '0000-00-00 00:00:00' ";
            }
            if ($sChkBought) {
                if (!empty($sWhere)) $sWhere .= "OR ";
                //$sWhere .= "(SELECT COUNT(*) FROM oxorder o1 WHERE o1.oxuserid=u.oxid) != 0 ";
                $sWhere .= "o.oxuserid IS NOT NULL AND n.oxunsubscribed = '0000-00-00 00:00:00' ";
            }
            $sWhere = "AND (" . $sWhere . ") ";
        }
        else
            $sWhere = "AND n.oxdboptin=999 ";
        

        $sSql = "SELECT u.oxid, n.oxsal, u.oxcustnr, n.oxfname, n.oxlname, u.oxcompany, n.oxemail, "
                    . "u.oxstreet, u.oxstreetnr, u.oxzip, u.oxcity, oxfon, (SELECT c.oxtitle FROM oxcountry c WHERE c.oxid=u.oxcountryid) AS oxcountry, "
                    . "o.oxlang, SUM(o.oxtotalordersum) AS oxrevenue, "
                    . "CASE n.oxdboptin "
                        . "WHEN 0 THEN IF(n.oxunsubscribed != '0000-00-00 00:00:00','unsubscribed',IF((SELECT COUNT(*) FROM oxorder o1 WHERE o1.oxuserid=u.oxid) = 0,'unknown','bought')) "
                        . "WHEN 1 THEN 'confirmed' "
                        . "WHEN 2 THEN 'unconfirmed' END "
                    . "AS oxstatus, "
                    . "u.oxregister "
                . "FROM oxnewssubscribed n "
                . "LEFT JOIN oxuser u "
                    . "ON (n.oxuserid=u.oxid) "
                . "LEFT JOIN oxorder o "
                    . "ON (u.oxid=o.oxuserid) "
                . "WHERE u.oxactive = 1 "
                . $sWhere
                . "GROUP BY u.oxid ";

        $aUsers = array();

        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        $rs = $oDb->Execute($sSql);
        while (!$rs->EOF) {
            array_push($aUsers, $rs->fields);
            $rs->MoveNext();
        }
        
        return $aUsers;
    }
 }
?>