<?PHP 
$main = [
    "common" => json_decode(file_get_contents(APPPATH.'Language/en/json/common.json'), true),
    "user" => json_decode(file_get_contents(APPPATH.'Language/en/json/user.json'), true),
    "button" => json_decode(file_get_contents(APPPATH.'Language/en/json/button.json'), true),
    "menu" => json_decode(file_get_contents(APPPATH.'Language/en/json/menu.json'), true),
    "addmsg" => json_decode(file_get_contents(APPPATH.'Language/en/json/addmsg.json'), true),
    "card_format" => json_decode(file_get_contents(APPPATH.'Language/en/json/card_format.json'), true),
];

return $main;
?>