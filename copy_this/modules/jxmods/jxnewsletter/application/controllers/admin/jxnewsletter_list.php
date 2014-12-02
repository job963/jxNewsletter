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

/*
 * 
 */
    public function render()
    {
        parent::render();
        
        $sChkAll = $this->getConfig()->getRequestParameter( 'jx_all' );
        $sChkConfirmed = $this->getConfig()->getRequestParameter( 'jx_confirmed' );
        $sChkUnconfirmed = $this->getConfig()->getRequestParameter( 'jx_unconfirmed' );
        $sChkUnsubscribed = $this->getConfig()->getRequestParameter( 'jx_unsubscribed' );
        $sChkBought = $this->getConfig()->getRequestParameter( 'jx_bought' );

        if (empty($sSrcVal))
            $sSrcVal = "";
        else
            $sSrcVal = strtoupper($sSrcVal);
        $this->_aViewData["jxnewsletter_srcval"] = $sSrcVal;

        $aUsers = $this->_retrieveData($sSrcVal);
        $this->_aViewData["aUsers"] = $aUsers;
        $this->_aViewData["jx_dbrows"] = count($aUsers);

        $this->_aViewData["jx_all"] = $sChkAll;
        $this->_aViewData["jx_confirmed"] = $sChkConfirmed;
        $this->_aViewData["jx_unconfirmed"] = $sChkUnconfirmed;
        $this->_aViewData["jx_unsubscribed"] = $sChkUnsubscribed;
        $this->_aViewData["jx_bought"] = $sChkBought;
        
        return $this->_sThisTemplate;
    }
    
    
    public function downloadResult()
    {
        $myConfig = oxRegistry::get("oxConfig");
        switch ( $myConfig->getConfigParam("sJxNewsletterSeparator") ) {
            case 'comma':
                $sSep = ',';
                break;
            case 'semicolon':
                $sSep = ';';
                break;
            case 'tab':
                $sSep = chr(9);
                break;
            case 'pipe':
                $sSep = '|';
                break;
            case 'tilde':
                $sSep = '~';
                break;
            default:
                $sSep = ',';
                break;
        }
        if ( $myConfig->getConfigParam("bJxNewsletterQuote") ) {
            $sBegin = '"';
            $sSep   = '"' . $sSep . '"';
            $sEnd   = '"';
        }
        
        $aUsers = array();
        $aUsers = $this->_retrieveData($sSrcVal);

        $sUsrIdList = $this->getConfig()->getRequestParameter( 'jxidlist' );
        
        $sContent = '';
        if ( $myConfig->getConfigParam("bJxNewsletterHeader") ) {
            $aHeader = array_keys($aUsers[0]);
            $sContent .= $sBegin . implode($sSep, $aHeader) . $sEnd . chr(13);
        }
        foreach ($aUsers as $aUser) {
            if ( strpos($sUsrIdList,$aUser['oxcustnr']) !== FALSE ) {
                $sContent .= $sBegin . implode($sSep, $aUser) . $sEnd . chr(13);
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
        $myConfig = oxRegistry::get("oxConfig");

        $aIncFiles = array();
        $aIncFields = array();
        if (count($myConfig->getConfigParam("aJxNewsletterIncludeFiles")) != 0) {
            $sIncFields = '';
            $aIncFiles = $myConfig->getConfigParam("aJxNewsletterIncludeFiles");
            $sIncPath = $this->jxGetModulePath() . '/application/controllers/admin/';
            foreach ($aIncFiles as $sIncFile) { 
                $sIncFile = $sIncPath . 'jxnewsletter_' . $sIncFile . '.inc.php';
                try {
                    require $sIncFile;
                }
                catch (Exception $e) {
                    echo $e->getMessage();
                    die();
                }
                $sIncFields .= ', ' . $aIncFields['field'];
            } 
        }
                
        $sChkAll = $this->getConfig()->getRequestParameter( 'jx_all' );
        $sChkConfirmed = $this->getConfig()->getRequestParameter( 'jx_confirmed' );
        $sChkUnconfirmed = $this->getConfig()->getRequestParameter( 'jx_unconfirmed' );
        $sChkUnsubscribed = $this->getConfig()->getRequestParameter( 'jx_unsubscribed' );
        $sChkBought = $this->getConfig()->getRequestParameter( 'jx_bought' );
        
        $sWhere = "";
        if ($sChkAll || $sChkConfirmed || $sChkConfirmed || $sChkUnconfirmed || $sChkBought) {
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
            if ($sChkAll) {
                $sWhere = " ";
            }
        }
        else
            $sWhere = "AND n.oxdboptin=999 "; // doesn't exists => empty result
        
        // Is the CUSTOMER NUMBER choosen
        if ( $myConfig->getConfigParam('bJxNewsletterCustNo') )
            $sCustNoField = 'u.oxcustnr,';
        else
            $sCustNoField = '';
        
        // Is the COMPANY choosen
        if ( $myConfig->getConfigParam('bJxNewsletterCompanys') )
            $sAddressField = 'u.oxcompany,';
        else
            $sAddressField = '';
        
        // Is the ADDRESS choosen
        if ( $myConfig->getConfigParam('bJxNewsletterAddress') )
            $sAddressField = 'u.oxstreet, u.oxstreetnr, u.oxzip, u.oxcity,';
        else
            $sAddressField = '';
        
        // Is the PHONE choosen
        if ( $myConfig->getConfigParam('bJxNewsletterPhone') )
            $sPhone = 'oxfon,';
        else
            $sPhone = '';
        
        // Is the COUNTRY choosen
        if ( $myConfig->getConfigParam('bJxNewsletterCountry') )
            $sCountry = '(SELECT c.oxtitle FROM oxcountry c WHERE c.oxid=u.oxcountryid) AS oxcountry,';
        else
            $sCountry = '';
        
        // Is the SUBSCRIBED choosen
        if ( $myConfig->getConfigParam('bJxNewsletterSubscribed') )
            $sSubscribed = ',DATE(n.oxsubscribed) AS oxsubscribed';
        else
            $sSubscribed = '';
        
        // Is the LANGUAGE choosen
        if ( $myConfig->getConfigParam('bJxNewsletterLanguage') )
            $sLanguage = 'o.oxlang,';
        else
            $sLanguage = '';
        
        // Is the REVENUE choosen
        if ( $myConfig->getConfigParam('bJxNewsletterRevenue') )
            $sRevenue = 'SUM(o.oxtotalordersum) AS oxrevenue,';
        else
            $sRevenue = '';
        
        // Is the ORDER COUNT choosen
        if ( $myConfig->getConfigParam('bJxNewsletterOrderCount') )
            $sOrderCount = 'COUNT(o.oxid) AS oxordercount,';
        else
            $sOrderCount = '';

        // Is the RETURN COUNT choosen
        if ( $myConfig->getConfigParam('bJxNewsletterReturnCount') )
            $sReturnCount = '(SELECT IFNULL(SUM(oa.oxamount),0) '
                            . 'FROM oxorder o1, oxorderarticles oa '
                            . 'WHERE u.oxid = o1.oxuserid '
                                . 'AND o1.oxid = oa.oxorderid '
                                . 'AND oa.oxstorno = 1) '
                            . 'AS oxreturncount,';
        else
            $sReturnCount = '';

        // Is the RETURN SUM choosen
        if ( $myConfig->getConfigParam('bJxNewsletterReturnSum') )
            $sReturnSum = '(SELECT IFNULL(SUM(oa.oxbrutprice),0.0) '
                            . 'FROM oxorder o1, oxorderarticles oa '
                            . 'WHERE u.oxid = o1.oxuserid '
                                . 'AND o1.oxid = oa.oxorderid '
                                . 'AND oa.oxstorno = 1) '
                            . 'AS oxreturnsum,';
        else
            $sReturnSum = '';

        $sSql = "SELECT u.oxid, n.oxsal, {$sCustNoField} n.oxfname, n.oxlname, {$sCompany} u.oxusername AS oxemail, "
                    . "{$sAddressField} {$sPhone} {$sCountry} "
                    . "{$sLanguage} {$sRevenue} {$sOrderCount} {$sReturnCount} {$sReturnSum} "
                    . "CASE n.oxdboptin "
                        . "WHEN 0 THEN IF(n.oxunsubscribed != '0000-00-00 00:00:00','unsubscribed',IF((SELECT COUNT(*) FROM oxorder o1 WHERE o1.oxuserid=u.oxid) = 0,'unknown','bought')) "
                        . "WHEN 1 THEN 'confirmed' "
                        . "WHEN 2 THEN 'unconfirmed' END "
                    . "AS oxstatus, "
                    . "DATE(u.oxregister) AS oxregister {$sSubscribed} "
                    . "{$sIncFields} "
                . "FROM oxuser u "
                . "LEFT JOIN oxnewssubscribed n "
                    . "ON (u.oxid = n.oxuserid) "
                . "LEFT JOIN oxorder o "
                    . "ON (u.oxid = o.oxuserid) "
                . "WHERE u.oxactive = 1 "
                . $sWhere
                . "GROUP BY u.oxid ";

        $aUsers = array();

        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
        //echo '<hr>' . $sSql . '<hr>';
        $rs = $oDb->Execute($sSql);
        while (!$rs->EOF) {
            array_push($aUsers, $rs->fields);
            $rs->MoveNext();
        }
        
        return $aUsers;
    }
 
    
    public function jxGetModulePath()
    {
        $sModuleId = $this->getEditObjectId();

        $this->_aViewData['oxid'] = $sModuleId;

        $oModule = oxNew('oxModule');
        $oModule->load($sModuleId);
        $sModuleId = $oModule->getId();
        
        $myConfig = oxRegistry::get("oxConfig");
        $sModulePath = $myConfig->getConfigParam("sShopDir") . 'modules/' . $oModule->getModulePath("jxnewsletter");
        
        return $sModulePath;
    }
    
}
?>