<?php
namespace App\Controllers;

class Help extends BaseController
{
    public $free_auth = true;

    public $pages = array(
        'alarm_map' => 'Dashboard',
        'alarm_set' => 'Dashboard Setting',
        'cam_view' => 'Camera View',
    );

    // ----------------------------------------------------------------------------------

    public function index()
    {
        $name = $this->input::get('name');
        $lang = $this->input::get('lang');
        $vars = ['baseController' => $this];
        // $this->display(array('lang'=>$lang, 'name'=>$name), 'help', FALSE);
        $this->display($vars, 'help/help', ['header' => 'header', 'footer' => 'footer', 'lang' => $lang, 'name' => $name]);
    }

    // ----------------------------------------------------------------------------------

    public function search()
    {
        $lang = $this->input::get('lang');
        $query = $this->input::get('query');

        if (empty($query)) {
            $query = ' ';
        }

        $pages = array();
        $cmd = "/spider/sicu/spider-help {$lang} '{$query}'";
        exec($cmd, $result);
        //var_dump($result);
        foreach ($result as $index => $line) {
            if (ereg("^FIND:([A-Za-z0-9\.\_]+)\.html,(.+)$", $line, $regs)) {
                //var_dump($regs);

                if (strtolower(substr($regs[2], 0, 8)) == 'help :: ') {
                    $regs[2] = substr($regs[2], 8);
                }

                $pages[$regs[1]] = $regs[2];
                //var_dump($pages);
            }
        }

        asort($pages);

        $this->display(array('language' => $lang, 'query' => $query, 'pages' => $pages), 'help_search', false);
    }

    // ----------------------------------------------------------------------------------

    public function main()
    {
        $name = $this->input::get('name');
        $lang = $this->input::get('lang');
        $path = APP_DIR . "/help/{$lang}/{$name}.html";

        if (file_exists($path)) {
            $this->output($path);
            //$this->util::redirect($path);
        } else {
            echo "Not exists file : {$path}";
        }
    }

    // ----------------------------------------------------------------------------------

}
