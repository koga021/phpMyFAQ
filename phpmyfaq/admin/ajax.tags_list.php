<?php
/**
* $Id: ajax.tags_list.php,v 1.3 2006-09-02 09:49:31 matteo Exp $
*
* AJAX: searches the tags
*
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @since        2005-12-15
* @copyright    (c) 2005-2006 phpMyFAQ Team
*
* The contents of this file are subject to the Mozilla Public License
* Version 1.1 (the "License"); you may not use this file except in
* compliance with the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
*
* Software distributed under the License is distributed on an "AS IS"
* basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
* License for the specific language governing rights and limitations
* under the License.
*/

if (!defined('IS_VALID_PHPMYFAQ_ADMIN')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/xml");
header("Vary: Negotiate,Accept");

require_once(PMF_ROOT_DIR.'/inc/Tags.php');
// TODO: manage the language correctly
$oTag = new PMF_Tags($db, 'en');
if (isset($_POST['autocomplete']) && is_string($_POST['autocomplete'])) {
    $tags = $oTag->getAllTags($db->escape_string($_POST['autocomplete']));
} else {
    $tags = $oTag->getAllTags();
}

if (count(ob_list_handlers()) > 0) {
    ob_clean();
}
?>
<ul>
<?php
if ($permission['editbt']) {
    $i = 0;
    foreach ($tags as $tagName) {
        $i++;
        if ($i <= PMF_TAGS_AUTOCOMPLETE_RESULT_SET_SIZE) {
            print('<li>'.$tagName.'<span class="informal"> ('.count($oTag->getRecordsByTagName($tagName)).')</span></li>');
        } elseif ($i == PMF_TAGS_AUTOCOMPLETE_RESULT_SET_SIZE + 1) {
        // Manage the "More results" info
            print('<li>'.$db->escape_string($_POST['autocomplete']).'<span class="informal">...</span></li>');
        }
    }
}
?>
</ul>
