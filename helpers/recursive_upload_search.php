<?php
function recursive_upload_search($haystack,$type) {
    foreach($haystack as $key=>$value) {
        
        if($value->thetype == $type) {
            return $value;
        }
    }
    return false;
}
?>