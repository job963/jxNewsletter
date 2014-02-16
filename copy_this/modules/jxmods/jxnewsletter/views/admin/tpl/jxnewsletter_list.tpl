[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]
<link href="[{$oViewConf->getModuleUrl('jxnewsletter','out/admin/src/jxnewsletter.css')}]" type="text/css" rel="stylesheet">

<script type="text/javascript">
    
var idlist = "";
var rowschecked = 0;
var updateDisplay = true;
    
  if(top)
  {
    top.sMenuItem    = "[{ oxmultilang ident="mxuadmin" }]";
    top.sMenuSubItem = "[{ oxmultilang ident="jxnewsletter_menu" }]";
    top.sWorkArea    = "[{$_act}]";
    top.setTitle();
  }


function editThis( sID, sClass )
{
    [{assign var="shMen" value=1}]

    [{foreach from=$menustructure item=menuholder }]
      [{if $shMen && $menuholder->nodeType == XML_ELEMENT_NODE && $menuholder->childNodes->length }]

        [{assign var="shMen" value=0}]
        [{assign var="mn" value=1}]

        [{foreach from=$menuholder->childNodes item=menuitem }]
          [{if $menuitem->nodeType == XML_ELEMENT_NODE && $menuitem->childNodes->length }]
            [{ if $menuitem->getAttribute('id') == 'mxorders' }]

              [{foreach from=$menuitem->childNodes item=submenuitem }]
                [{if $submenuitem->nodeType == XML_ELEMENT_NODE && $submenuitem->getAttribute('cl') == 'admin_order' }]

                    if ( top && top.navigation && top.navigation.adminnav ) {
                        var _sbli = top.navigation.adminnav.document.getElementById( 'nav-1-[{$mn}]-1' );
                        var _sba = _sbli.getElementsByTagName( 'a' );
                        top.navigation.adminnav._navAct( _sba[0] );
                    }

                [{/if}]
              [{/foreach}]

            [{ /if }]
            [{assign var="mn" value=$mn+1}]

          [{/if}]
        [{/foreach}]
      [{/if}]
    [{/foreach}]

    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value=sClass; /*'article';*/
    oTransfer.submit();
}


function change_all( name, elem )
{
    if(!elem || !elem.form) 
        return alert("Check Parameters");

    var chkbox = elem.form.elements[name];
    if (!chkbox) 
        return alert(name + " doesn't exist!");

    updateDisplay = false;
    if (!chkbox.length) 
        chkbox.checked = elem.checked; 
    else 
        for(var i = 0; i < chkbox.length; i++) {
            chkbox[i].checked = elem.checked;
            changeColor(elem.checked,i);
        }
    updateDisplay = true;
    document.getElementById('rowschecked').innerHTML = rowschecked;
    if (rowschecked > 0)
        document.getElementById('btndownload').disabled = false;
    else
        document.getElementById('btndownload').disabled = true;
}


function implode_all( name, elem )
{
    if(!elem || !elem.form) 
        return alert("Check Parameters");

    var chkbox = elem.form.elements[name];
    if (!chkbox) 
        return alert(name + " doesn't exist!");

    if (!chkbox.length) 
        chkbox.checked = false; 
    else 
        for(var i = 0; i < chkbox.length; i++) {
            if (chkbox[i].checked)
                idlist = idlist + ',' + chkbox[i].value;
        }
}


function changeColor(checkValue,rowNumber)
{
    aColumns = new Array("jxSal", "jxEmail", "jxFname", "jxLname", "jxCompany", "jxCity", "jxCountry", "jxRevenue");
    if (checkValue) {
        for (var i = 0; i < aColumns.length; i++) {
            elemName = aColumns[i] + rowNumber;
            document.getElementById(elemName).style.color = "blue";
            document.getElementById(elemName).style.fontWeight = "bold";
        }
        rowschecked++;
    } else {
        for (var i = 0; i < aColumns.length; i++) {
            elemName = aColumns[i] + rowNumber;
            document.getElementById(elemName).style.color = "black";
            document.getElementById(elemName).style.fontWeight = "normal";
        }
        rowschecked--;
    }
    if (rowschecked <= 0) {
        rowschecked = 0;
        document.getElementById('maincheck').checked = false;
    }
    if (rowschecked > [{$jx_dbrows}]) {
        rowschecked = [{$jx_dbrows}];
    }
    if (updateDisplay) {
        document.getElementById('rowschecked').innerHTML = rowschecked;
        if (rowschecked > 0)
            document.getElementById('btndownload').disabled = false;
        else
            document.getElementById('btndownload').disabled = true;
    }
}

</script>

    <h1>[{ oxmultilang ident="JXNEWSLETTER_TITLE" }]</h1>
    <form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
        [{ $shop->hiddensid }]
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="cl" value="article" size="40">
        <input type="hidden" name="updatelist" value="1">
    </form>
        
<form name="jxnewsletter" id="jxnewsletter" action="[{ $oViewConf->selflink }]" method="post">
    <p>
        [{ $oViewConf->hiddensid }]
        <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
        <input type="hidden" name="cl" value="jxnewsletter_list">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="jxidlist" value="">
        <table><tr>
        <td align="left">
            <fieldset style="width:200px;">
                <legend><b> [{ oxmultilang ident="JXNEWSLETTER_FILTER" }] ([{$jx_dbrows}]) </b></legend>
                <input type="checkbox" id="jx_all" name="jx_all" value="jx_all" [{if $jx_all=="jx_all"}]checked="checked"[{/if}] 
                       onclick="document.getElementById('jx_confirmed').checked=document.getElementById('jx_all').checked;
                                document.getElementById('jx_unconfirmed').checked=document.getElementById('jx_all').checked;
                                document.getElementById('jx_unsubscribed').checked=document.getElementById('jx_all').checked;
                                document.getElementById('jx_bought').checked=document.getElementById('jx_all').checked;
                                document.forms['jxnewsletter'].elements['fnc'].value = '';
                                document.forms.jxnewsletter.submit();">
                <label for="jx_all"> [{ oxmultilang ident="JXNEWSLETTER_ALL" }]</label><br />
                
                <input type="checkbox" id="jx_confirmed" name="jx_confirmed" value="jx_confirmed" [{if $jx_confirmed=="jx_confirmed"}]checked="checked"[{/if}] 
                       onclick="document.forms['jxnewsletter'].elements['fnc'].value = '';
                                document.getElementById('jx_all').checked=false;
                                document.forms.jxnewsletter.submit();">
                <label for="jx_confirmed"> <img src="[{$oViewConf->getModuleUrl('jxnewsletter','out/admin/src/bg/ico_active.png')}]" /> [{ oxmultilang ident="JXNEWSLETTER_CONFIRMED" }]</label><br />
                
                <input type="checkbox" id="jx_unconfirmed" name="jx_unconfirmed" value="jx_unconfirmed" [{if $jx_unconfirmed=="jx_unconfirmed"}]checked="checked"[{/if}] 
                       onclick="document.forms['jxnewsletter'].elements['fnc'].value = '';
                                document.getElementById('jx_all').checked=false;
                                document.forms.jxnewsletter.submit();">
                <label for="jx_unconfirmed"> <img src="[{$oViewConf->getModuleUrl('jxnewsletter','out/admin/src/bg/ico_unconfirmed.png')}]" /> [{ oxmultilang ident="JXNEWSLETTER_UNCONFIRMED" }]</label><br />
                
                <input type="checkbox" id="jx_unsubscribed" name="jx_unsubscribed" value="jx_unsubscribed" [{if $jx_unsubscribed=="jx_unsubscribed"}]checked="checked"[{/if}] 
                       onclick="document.forms['jxnewsletter'].elements['fnc'].value = '';
                                document.getElementById('jx_all').checked=false;
                                document.forms.jxnewsletter.submit();">
                <label for="jx_unsubscribed"> <img src="[{$oViewConf->getModuleUrl('jxnewsletter','out/admin/src/bg/ico_unsubscribed.png')}]" /> [{ oxmultilang ident="JXNEWSLETTER_UNSUBSCRIBED" }]</label><br />
                
                <input type="checkbox" id="jx_bought" name="jx_bought" value="jx_bought" [{if $jx_bought=="jx_bought"}]checked="checked"[{/if}] 
                       onclick="document.forms['jxnewsletter'].elements['fnc'].value = '';
                                document.getElementById('jx_all').checked=false;
                                document.forms.jxnewsletter.submit();">
                <label for="jx_bought"> <img src="[{$oViewConf->getModuleUrl('jxnewsletter','out/admin/src/bg/ico_bought.png')}]" /> [{ oxmultilang ident="JXNEWSLETTER_BOUGHT" }]</label><br />
            </fieldset>
        </td>
        <td valign="top">
            <fieldset style="width:200px;">
                <legend><b> [{ oxmultilang ident="JXNEWSLETTER_EXPORT" }] (<span id="rowschecked">0</span>)</b></legend>
                [{ oxmultilang ident="JXNEWSLETTER_EXPLAIN" }]
                <br /><br />
                <input class="edittext" type="submit" id="btndownload" disabled="disabled"
                    onClick="implode_all('jxnewsletter_oxid[]', this);
                             document.forms['jxnewsletter'].elements['jxidlist'].value = idlist;
                             document.forms['jxnewsletter'].elements['fnc'].value = 'downloadResult';" 
                    value=" [{ oxmultilang ident="JXNEWSLETTER_DOWNLOAD" }] " [{ $readonly }]>
            </fieldset>
        </td>
        </tr></table>
    </p>

    <div id="liste">
        <table cellspacing="0" cellpadding="0" border="0" width="99%">
        <tr>
            [{ assign var="headStyle" value="border-bottom:1px solid #C8C8C8; font-weight:bold;" }]
            <td class="listfilter first" style="[{$headStyle}]" height="15" width="30" align="center">
                <div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</div></div>
            </td>
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_EMAIL" }]</div></div></td>
            [{*<td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="USER_MAIN_CUSTOMERSNR" }]</div></div></td>*}]
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_BILLSAL" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ORDER_LIST_CUSTOMERFNAME" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="ORDER_LIST_CUSTOMERLNAME" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_COMPANY" }]</div></div></td>
            [{*<td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_TELEPHONE" }]</div></div></td>*}]
            [{*<td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="USER_MAIN_STRNR" }]</div></div></td>*}]
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="USER_LIST_ZIP" }]/[{ oxmultilang ident="USER_LIST_PLACE" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="GENERAL_COUNTRY" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]"><div class="r1"><div class="b1">[{ oxmultilang ident="JXNEWSLETTER_REVENUE" }]</div></div></td>
            <td class="listfilter" style="[{$headStyle}]" align="center"><div class="r1"><div class="b1"><input type="checkbox" onclick="change_all('jxnewsletter_oxid[]', this)" id="maincheck"></div></div></td>
        </tr>

        [{ assign var="actArtTitle" value="..." }]
        [{ assign var="actArtVar" value="..." }]
        [{ assign var="i" value=0 }]
        [{foreach name=outer item=User from=$aUsers}]
            <tr>
                [{ cycle values="listitem,listitem2" assign="listclass" }]
                <td valign="top" 
                    class="[{$listclass}]
                        [{ if $User.oxstatus == "confirmed"}] active
                        [{elseif $User.oxstatus == "unconfirmed"}] unconfirmed
                        [{elseif $User.oxstatus == "unsubscribed"}] unsubscribed
                        [{elseif $User.oxstatus == "bought"}] bought
                        [{/if}]" 
                    height="15" 
                    [{ if $User.oxstatus == "confirmed"}]title="active"
                        [{elseif $User.oxstatus == "unconfirmed"}]title="unconfirmed"
                        [{elseif $User.oxstatus == "unsubscribed"}]title="unsubscribed"
                        [{elseif $User.oxstatus == "bought"}]title="bought"
                        [{else}]
                        [{/if}]>
                    <div class="listitemfloating">&nbsp</a></div>
                </td>
                <td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxEmail[{$i}]">
                       [{$User.oxemail}]
                    </a>
                </td>
                [{*<td class="[{$listclass}]" align="center">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxCustnr[{$i}]">
                       [{$User.oxcustnr}]
                    </a>
                </td>*}]
                <td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_order');" id="jxSal[{$i}]">
                       [{$User.oxsal|oxmultilangsal}]
                    </a>
                </td>
                <td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxFname[{$i}]">
                       [{$User.oxfname}]
                    </a>
                </td>
                <td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxLname[{$i}]">
                       [{$User.oxlname}]
                    </a>
                </td>
                <td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxCompany[{$i}]">
                       [{$User.oxcompany}]
                    </a>
                </td>
                [{*<td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxFon[{$i}]">
                       [{$User.oxfon}]
                    </a>&nbsp; 
                </td>*}]
                [{*<td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxStreet[{$i}]">
                       [{$User.oxstreet}] [{$User.oxstreetnr}]
                    </a>
                </td>*}]
                <td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxCity[{$i}]">
                       [{$User.oxzip}] [{$User.oxcity}]
                    </a>
                </td>
                <td class="[{$listclass}]">
                    <a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxCountry[{$i}]">
                       [{$User.oxcountry}]
                    </a>
                </td>
                <td class="[{$listclass}]" align="right">
                    &nbsp;<a href="Javascript:editThis('[{$User.oxid}]','admin_user');" id="jxRevenue[{$i}]">
                       [{$User.oxrevenue|string_format:"%.2f"}]
                    </a>&nbsp;
                </td>
                <td class="[{$listclass}]" align="center">
                    <input type="checkbox" name="jxnewsletter_oxid[]" 
                        onclick="changeColor(this.checked,[{$i}]);" 
                        value="[{$User.oxcustnr}]">
                </td>
                [{ assign var="i" value=$i+1 }]
            </tr>
        [{/foreach}]

        </table>
    </div>
</form>
