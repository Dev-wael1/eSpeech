<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use IonAuth\Libraries\IonAuth;
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

class BaseController extends Controller
{
    /**
     * IonAuth library
     *
     * @var \IonAuth\Libraries\IonAuth
     */
    protected $ionAuth;
    protected $isLoggedIn;
    protected $user;
    protected $userIsAdmin;
    protected $userIdentity;
    protected $userId;
    // public $settings;
    // public $appName;

    /**
     * Instance of the main Request object.
     *
     * @var IncomingRequest|CLIRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['function', 'synthesize', 'url', 'form'];

    /**
     * Constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     */

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {

        helper('function');
        \CodeIgniter\Events\Events::trigger('post_controller_constructor');
        
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $session = \Config\Services::session();
        $language = \Config\Services::language();
        $language->setLocale($session->lang);
        
        //--------------------------------------------------------------------
        
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.: $this->session = \Config\Services::session();

        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->updateUser();
        
    }
    
    
    protected function updateUser()
    {
        
        $this->isLoggedIn = $this->ionAuth->loggedIn();
        if ($this->isLoggedIn) {
            $this->user = $this->ionAuth->user()->row()->first_name;
            $this->userIsAdmin = $this->ionAuth->isAdmin();
            $this->userId = $this->ionAuth->user()->row()->id;
            $this->userIdentity = $this->ionAuth->user()->row()->email;
        } else {
            $this->user = NULL;
            $this->userIsAdmin = NULL;
        }
    }
}
