<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\CommonModel;

// import custorm libraries
use App\Libraries\Input;
use App\Libraries\Util;
use App\Libraries\Log;
use App\Libraries\Pagination;
use App\Libraries\EnumTable;


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
  protected $networkModel;
  protected $defaultModel;

  protected $input;
  protected $util;
  protected $log;
  protected $pagination;
  protected $free_auth  = FALSE;
  protected $enumtable;

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
    $this->defaultModel = new CommonModel('default');
    $this->networkModel = new CommonModel('network');

    // custom Libraries
    $this->input = new Input();
    $this->util = new Util();
    $this->log = new Log();
    $this->pagination = new Pagination();
    $this->enumtable = new EnumTable();
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

  public function display($vars = array(), $view = '', $layout = '')
  {

    $content = ($layout['header']) ? view('common/'.$layout['header']):'';
    $content .= view($view, $vars);
    $content .= ($layout['footer']) ? view('common/'.$layout['footer']):'';
    echo $content;
  }

  public function is_auth($FormIndex, $Authority)
  {
		if( $_SESSION['spider_type'] == 'spider' )  return TRUE;
		if( $this->free_auth )  return TRUE;

		$userroletable  = $this->defaultModel->selectRowsCount("UserRoleTable", ["Site" => $_SESSION['spider_site'], "UserRole" => $_SESSION['spider_userrole'], "FormIndex" => $FormIndex , "Authority" => $Authority ]); 

    if( $userroletable > 0 )
      return TRUE;
    else						
    return FALSE;
  }
  
}