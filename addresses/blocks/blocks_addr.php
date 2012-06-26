<?php   
/**
 * ****************************************************************************
 *  - A Project by Developers TEAM For Xoops - ( http://www.xoops.org )
 * ****************************************************************************
 *  ADDRESSES - MODULE FOR XOOPS
 *  Copyright (c) 2007 - 2012
 *  TXMod Xoops (Timgno) ( http://www.txmodxoops.org )
 *  Created by TDMCreate version 1.37
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  ---------------------------------------------------------------------------
 *  @copyright  TXMod Xoops (Timgno) ( http://www.txmodxoops.org )
 *  @license    GNU GPL see License
 *  @since      2.5.0
 *  @package    addresses
 *  @author     TXMod Xoops (Timgno) ( support@txmodxoops.org )
 *
 *  Version : 1.73 Tue 2012/06/26 13:30:40 : Timgno Exp $
 * ****************************************************************************
 */
	
include_once XOOPS_ROOT_PATH."/modules/addresses/include/functions.php";
	
function b_addresses_addr($options) {
include_once XOOPS_ROOT_PATH."/modules/addresses/class/addr.php";
$myts =& MyTextSanitizer::getInstance();

$addr = array();
$type_block = $options[0];
$nb_addr = $options[1];
$lenght_title = $options[2];

$addrHandler =& xoops_getModuleHandler("addresses_addr", "addresses");
$criteria = new CriteriaCompo();
array_shift($options);
array_shift($options);
array_shift($options);

switch ($type_block) 
{
	// pour le bloc: addr recents
	case "recent":
		$criteria->add(new Criteria("addr_online", 1));
		$criteria->setSort("addr_date_created");
		$criteria->setOrder("DESC");
	break;
	// pour le bloc: addr du jour
	case "day":	
		$criteria->add(new Criteria("addr_online", 1));
		$criteria->add(new Criteria("addr_date_created", strtotime(date("Y/m/d")), ">="));
		$criteria->add(new Criteria("addr_date_created", strtotime(date("Y/m/d"))+86400, "<="));
		$criteria->setSort("addr_date_created");
		$criteria->setOrder("ASC");
	break;
	// pour le bloc: addr aléatoires
	case "random":
		$criteria->add(new Criteria("addr_online", 1));
		$criteria->setSort("RAND()");
	break;
}


$criteria->setLimit($nb_addr);
$addr_arr = $addrHandler->getall($criteria);
	foreach (array_keys($addr_arr) as $i) 
	{
		$addr[$i]["addr_id"] = $addr_arr[$i]->getVar("addr_id");
			$addr[$i]["addr_cid"] = $addr_arr[$i]->getVar("addr_cid");
			$addr[$i]["addr_title"] = $addr_arr[$i]->getVar("addr_title");
		
	}
return $addr;
}

function b_addresses_addr_edit($options) {
	$form = ""._MB_ADDRESSES_ADDR_DISPLAY."\n";
	$form .= "<input type=\"hidden\" name=\"options[0]\" value=\"".$options[0]."\" />";
	$form .= "<input name=\"options[1]\" size=\"5\" maxlength=\"255\" value=\"".$options[1]."\" type=\"text\" />&nbsp;<br />";
	$form .= ""._MB_ADDRESSES_ADDR_TITLELENGTH." : <input name=\"options[2]\" size=\"5\" maxlength=\"255\" value=\"".$options[2]."\" type=\"text\" /><br /><br />";
	array_shift($options);
	array_shift($options);
	array_shift($options);
	$form .= ""._MB_ADDRESSES_ADDR_CATTODISPLAY."<br /><select name=\"options[]\" multiple=\"multiple\" size=\"5\">";
	$form .= "<option value=\"0\" " . (array_search(0, $options) === false ? "" : "selected=\"selected\"") . ">" ._MB_ADDRESSES_ADDR_ALLCAT . "</option>";
	foreach (array_keys($topic_arr) as $i) {
		$form .= "<option value=\"" . $topic_arr[$i]->getVar("topic_id") . "\" " . (array_search($topic_arr[$i]->getVar("topic_id"), $options) === false ? "" : "selected=\"selected\"") . ">".$topic_arr[$i]->getVar("topic_title")."</option>";
	}
	$form .= "</select>";

	return $form;
}
	
?>