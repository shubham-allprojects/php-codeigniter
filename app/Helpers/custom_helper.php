<?PHP
function is_option( $index ) {
    if(substr(session()->get('spider_option'), $index, 1) == '1') {
        return TRUE;
    }
    return FALSE;
}

?>