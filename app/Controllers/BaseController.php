<?php

namespace App\Controllers;

use App\Libraries\CustomLanguage;
use App\Libraries\EnumTable;
use App\Libraries\Input;
use App\Libraries\Log;
use App\Libraries\Pagination;
use App\Libraries\Util;

/**
 * 04-08-2023
 * import custom libraries
 */
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $net_work;
    protected $conn_log;
    protected $conn;
    protected $api_conn;

    protected $input;
    protected $util;
    protected $log;
    protected $pagination;
    protected $free_auth = false;
    protected $enumtable;
    protected $lang;
    protected $result;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        $this->session = session();

        /**
         * 04-08-2023
         * CI4 doesn't support PDO DB connection.
         * And in original code base everywhere we have preapred statements
         * Replacing prepared statement with sqlite3 queries is time consuming process.
         * To avoid that initializing PDO DB connection from PHP PDO dll file.
         */
        $this->conn = new \PDO('sqlite:' . WRITEPATH . 'database/Spider.db'); // using same object name as original
        $this->conn_log = new \PDO('sqlite:' . WRITEPATH . 'database/SpiderLog.db'); // using same object name as original
        $this->net_work = new \PDO('sqlite:' . WRITEPATH . 'database/Network.db'); // using same object name as original
        $this->api_conn = new \PDO('sqlite:' . WRITEPATH . 'database/api.db'); // using same object name as original

        /**
         * Initialize the object of libraries.
         */
        $this->input = new Input();
        $this->util = new Util();
        $this->log = new Log();
        $this->pagination = new Pagination();
        $this->enumtable = new EnumTable();
        $this->lang = new CustomLanguage();
    }

    public function systemLogger($msg = "", $tag = "web")
    {
        try {
            exec(SPIDER_COMM . " syslog $tag '$msg'");
        } catch (\Exception $e) {
            error_log("Not able to send this message[$tag in msg->'$msg']");
        }
    }

    /**
     * load views based on passed conditions
     */

    public function display($data = [], $pageBody = '', $HeaderFooterArr = [])
    {
        $data['lang'] = $this->lang;
        $data['Input'] = $this->input;

        $content = ($HeaderFooterArr['header']) ? view('common/' . $HeaderFooterArr['header'], $data) : ''; // common header for page content
        $content .= view($pageBody, $data); // main page content
        $content .= ($HeaderFooterArr['footer']) ? view('common/' . $HeaderFooterArr['footer'], $data) : ''; // common footer for page content

        echo $content;
    }

    public function is_auth($FormIndex, $Authority)
    {
        if ($_SESSION['spider_type'] == 'spider') {
            return true;
        }

        if ($this->free_auth) {
            return true;
        }

        $userroletable = $this->conn->prepare("SELECT COUNT(*) FROM UserRoleTable WHERE Site = ? AND UserRole = ? AND FormIndex = ? AND Authority = ?");
        $userroletable->execute(array($_SESSION['spider_site'], $_SESSION['spider_userrole'], $FormIndex, $Authority));
        $userroletable = $userroletable->fetchColumn();

        if ($userroletable > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function is_SuperAdmin()
    {
        if ($_SESSION['spider_type'] == 'spider') {
            return true;
        } else {
            return false;
        }

    }

}
