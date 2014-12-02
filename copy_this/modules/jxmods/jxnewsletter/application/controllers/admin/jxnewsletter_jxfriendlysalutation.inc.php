<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$aIncFields = array("name"  => "jxfriendlysalutation", 
                    "field" => "IF(n.oxsal='MR','Lieber',IF(n.oxsal='MRS','Liebe','')) AS jxfriendlysalutation" 
                       );
