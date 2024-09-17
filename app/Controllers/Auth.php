<?php

namespace App\Controllers;

/**
 * Class Auth
 *
 * @property Ion_auth|Ion_auth_model $ion_auth      The ION Auth spark
 * @package  CodeIgniter-Ion-Auth
 * @author   Ben Edmunds <ben.edmunds@gmail.com>
 * @author   Benoit VRIGNAUD <benoit.vrignaud@zaclys.net>
 * @license  https://opensource.org/licenses/MIT	MIT License
 */
class Auth extends BaseController
{

    /**
     *
     * @var array
     */
    public $data = [];

    /**
     * Configuration
     *
     * @var \IonAuth\Config\IonAuth
     */
    protected $configIonAuth;

    /**
     * IonAuth library
     *
     * var \IonAuth\Libraries\IonAuth
     */


    /**
     * Session
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Validation library
     *
     * @var \CodeIgniter\Validation\Validation
     */
    protected $validation;

    /**
     * Validation list template.
     *
     * @var string
     * @see https://bcit-ci.github.io/CodeIgniter4/libraries/validation.html#configuration
     */
    protected $validationListTemplate = 'list';

    /**
     * Views folder
     * Set it to 'auth' if your views files are in the standard application/Views/auth
     *
     * @var string
     */

    protected $viewsFolder = '/frontend/retro';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->data['admin'] = $this->userIsAdmin;


        $this->validation = \Config\Services::validation();
        helper(['form', 'url']);
        $this->configIonAuth = config('IonAuth');
        $this->session       = \Config\Services::session();

        if (!empty($this->configIonAuth->templates['errors']['list'])) {
            $this->validationListTemplate = $this->configIonAuth->templates['errors']['list'];
        }
    }

    /**
     * Redirect if needed, otherwise display the user list
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        if (!$this->ionAuth->loggedIn()) {
            // redirect them to the login page
            return redirect()->to('/auth/login');
        } else {
            if ($this->ionAuth->isAdmin()) {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/user/dashboard');
            }
        }
    }

    /**
     * Log the user in
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function login()
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['admin'] = true;
        } else {
            $this->data['admin'] = false;
        }
        if ($this->ionAuth->loggedIn()) {

            if ($this->ionAuth->isAdmin()) {
                return redirect()->to('/admin/dashboard')->withCookies();
            } else {
                return redirect()->to('/user/dashboard')->withCookies();
            }
        } else {
            $this->data['title'] = lang('Auth.login_heading');

            // validate form input
            $this->validation->setRule('identity', str_replace(':', '', lang('Auth.login_identity_label')), 'required');
            $this->validation->setRule('password', str_replace(':', '', lang('Auth.login_password_label')), 'required');

            if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {
                // check to see if the user is logging in
                // check for "remember me"
                $remember = (bool)$this->request->getVar('remember');

                if ($this->ionAuth->login($this->request->getVar('identity'), $this->request->getVar('password'), $remember)) {
                    $this->is_loggedin = true;
                    $this->session->setFlashdata('message', $this->ionAuth->messages());
                    $this->data['title'] = "Dashboard | $app_name - Voice Synthesis Services";
                    if (exists(["username" => $this->request->getVar('identity')], 'users')) {
                        $this->session->setFlashdata('message',);
                    }
                    $this->data['main_page'] = "auth/index";
                    $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
                    $this->data['meta_description'] = "Deshboard. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";


                    if ($this->ionAuth->isAdmin()) {
                        return redirect()->to('/admin/dashboard')->withCookies();
                    } else {
                        return redirect()->to('/user/dashboard')->withCookies();
                    }
                } else {
                    // if the login was un-successful
                    // redirect them back to the login page

                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                    // use redirects instead of loading views for compatibility with MY_Controller libraries
                    return redirect()->back()->withInput();
                }
            } else {
                // the user is not logging in so display the login page
                // set the flash data error message if there is one
                $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->data['identity'] = [
                    'name'  => 'identity',
                    'id'    => 'identity',
                    'type'  => 'text',
                    'value' => set_value('identity'),
                ];

                $this->data['password'] = [
                    'name' => 'password',
                    'id'   => 'password',
                    'type' => 'password',
                ];
                $this->data['title'] = "Login &mdash; $app_name - Voice Synthesis Services";
                $this->data['main_page'] = "login";
                $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
                $this->data['meta_description'] = "Login to $app_name. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
                return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
            }
        }
    }

    /**
     * Log the user out
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        $this->data['title'] = 'Logout';

        // log the user out
        $this->ionAuth->logout();

        // redirect them to the login page
        $this->session->setFlashdata('message', "");

        $this->session->setFlashdata('logout_msg', "<ul class='mb-0'><li>Logged Out Successfully</li><ul>");
        $this->is_loggedin = false;
        return redirect()->to('/auth/login')->withCookies();
    }

    /**
     * Change password
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function change_password()
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        if (!$this->ionAuth->loggedIn()) {
            return redirect()->to('/auth/login');
        }

        $this->validation->setRule('old', lang('Auth.change_password_validation_old_password_label'), 'required');
        $this->validation->setRule('new', lang('Auth.change_password_validation_new_password_label'), 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
        $this->validation->setRule('new_confirm', lang('Auth.change_password_validation_new_password_confirm_label'), 'required');

        $user = $this->ionAuth->user()->row();

        if (!$this->request->getPost() || $this->validation->withRequest($this->request)->run() === false) {
            // display the form
            // set the flash data error message if there is one
            $this->data['message'] = ($this->validation->getErrors()) ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

            $this->data['minPasswordLength'] = $this->configIonAuth->minPasswordLength;
            $this->data['old_password'] = [
                'name' => 'old',
                'id'   => 'old',
                'type' => 'password',
            ];
            $this->data['new_password'] = [
                'name'    => 'new',
                'id'      => 'new',
                'type'    => 'password',
                'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
            ];
            $this->data['new_password_confirm'] = [
                'name'    => 'new_confirm',
                'id'      => 'new_confirm',
                'type'    => 'password',
                'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
            ];
            $this->data['user_id'] = [
                'name'  => 'user_id',
                'id'    => 'user_id',
                'type'  => 'hidden',
                'value' => $user->id,
            ];

            // render
            $this->data['title'] = "Change Password | $app_name - Voice Synthesis Services";
            $this->data['main_page'] = "auth/change_password";
            $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
            $this->data['meta_description'] = "Change the $app_name account password. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
        } else {
            $identity = $this->session->get('identity');

            $change = $this->ionAuth->changePassword($identity, $this->request->getPost('old'), $this->request->getPost('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->setFlashdata('message', $this->ionAuth->messages());
                return $this->logout();
            } else {
                $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                return redirect()->to('/auth/change_password');
            }
        }
    }

    /**
     * Forgot password
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function forgot_password()
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        $this->data['title'] = lang('Auth.forgot_password_heading');

        // setting validation rules by checking whether identity is username or email
        if ($this->configIonAuth->identity !== 'email') {
            $this->validation->setRule('identity', lang('Auth.forgot_password_identity_label'), 'required');
        } else {
            $this->validation->setRule('identity', lang('Auth.forgot_password_validation_email_label'), 'required|valid_email');
        }

        if (!($this->request->getPost() && $this->validation->withRequest($this->request)->run())) {

            $this->data['type'] = $this->configIonAuth->identity;
            // setup the input
            $this->data['identity'] = [
                'name' => 'identity',
                'id'   => 'identity',
            ];

            if ($this->configIonAuth->identity !== 'email') {
                $this->data['identity_label'] = lang('Auth.forgot_password_identity_label');
            } else {
                $this->data['identity_label'] = lang('Auth.forgot_password_email_identity_label');
            }


            // set any errors and display the form
            $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
            $this->data['title'] = "Reset Password | $app_name - Voice Synthesis Services";
            $this->data['main_page'] = "forgot_password";
            $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
            $this->data['meta_description'] = "Resetting forgot password of $app_name account. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
        } else {
            $identityColumn = $this->configIonAuth->identity;
            $identity = $this->ionAuth->where($identityColumn, $this->request->getPost('identity'))->users()->row();
            $check = (array)$identity;

            if (empty($check)) {
                $this->session->setFlashdata('no_id', '<div class="alert alert-danger pb-0" id="infoMessage">
                <ul>
                    <li>User does not Exists</li>
                </ul>
            </div>');
                return redirect()->to('/auth/forgot-password');
            }

            if (empty($identity)) {
                if ($this->configIonAuth->identity !== 'email') {
                    $this->ionAuth->setError('Auth.forgot_password_identity_not_found');
                } else {
                    $this->ionAuth->setError('Auth.forgot_password_email_not_found');
                }

                $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                return redirect()->to('/auth/forgot-password');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ionAuth->forgottenPassword($identity->{$this->configIonAuth->identity});

            if ($forgotten) {
                // if there were no errors
                $this->session->setFlashdata('message', $this->ionAuth->messages());
                return redirect()->to('/auth/login'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));

                return redirect()->to('/auth/forgot-password');
            }
        }
    }

    /**
     * Reset password - final step for forgotten password
     *
     * @param string|null $code The reset code
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function reset_password($code = null)
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        if (!$code) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->data['title'] = lang('Auth.reset_password_heading');

        $user = $this->ionAuth->forgottenPasswordCheck($code);

        if ($user) {
            // if the code is valid then display the password reset form

            $this->validation->setRule('new', lang('Auth.reset_password_validation_new_password_label'), 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
            $this->validation->setRule('new_confirm', lang('Auth.reset_password_validation_new_password_confirm_label'), 'required');

            if (!$this->request->getPost() || $this->validation->withRequest($this->request)->run() === false) {
                // display the form

                // set the flash data error message if there is one
                $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');

                $this->data['minPasswordLength'] = $this->configIonAuth->minPasswordLength;
                $this->data['new_password'] = [
                    'name'    => 'new',
                    'id'      => 'new',
                    'type'    => 'password',
                    'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
                ];
                $this->data['new_password_confirm'] = [
                    'name'    => 'new_confirm',
                    'id'      => 'new_confirm',
                    'type'    => 'password',
                    'pattern' => '^.{' . $this->data['minPasswordLength'] . '}.*$',
                ];
                $this->data['user_id'] = [
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                ];
                $this->data['code'] = $code;

                // render
                $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->data['title'] = "Reset password | $app_name - Voice Synthesis Services";
                $this->data['main_page'] = "auth/reset_password";
                $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
                $this->data['meta_description'] = "Resetting forgot password of $app_name account. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
                return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
            } else {
                $identity = $user->{$this->configIonAuth->identity};

                // do we have a valid request?
                if ($user->id != $this->request->getPost('user_id')) {
                    // something fishy might be up
                    $this->ionAuth->clearForgottenPasswordCode($identity);

                    throw new \Exception(lang('Auth.error_security'));
                } else {
                    // finally change the password
                    $change = $this->ionAuth->resetPassword($identity, $this->request->getPost('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->setFlashdata('message', $this->ionAuth->messages());
                        return redirect()->to('/auth/login');
                    } else {
                        $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                        return redirect()->to('/auth/reset-password/' . $code);
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
            return redirect()->to('/auth/forgot-password');
        }
    }

    /**
     * Activate the user
     *
     * @param integer $id   The user ID
     * @param string  $code The activation code
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function activate(int $id, string $code = ''): \CodeIgniter\HTTP\RedirectResponse
    {
        $activation = false;

        if ($code) {
            $activation = $this->ionAuth->activate($id, $code);
        } else if ($this->ionAuth->isAdmin()) {
            $activation = $this->ionAuth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->setFlashdata('message', $this->ionAuth->messages());
            return redirect()->to('/auth');
        } else {
            // redirect them to the forgot password page
            $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
            return redirect()->to('/auth/forgot_password');
        }
    }

    /**
     * Deactivate the user
     *
     * @param integer $id The user ID
     *
     * @throw Exception
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function deactivate(int $id = 0)
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        if (!$this->ionAuth->loggedIn() || !$this->ionAuth->isAdmin()) {
            // redirect them to the home page because they must be an administrator to view this
            throw new \Exception('You must be an administrator to view this page.');
        }

        $this->validation->setRule('confirm', lang('Auth.deactivate_validation_confirm_label'), 'required');
        $this->validation->setRule('id', lang('Auth.deactivate_validation_user_id_label'), 'required|integer');

        if (!$this->validation->withRequest($this->request)->run()) {
            $this->data['user'] = $this->ionAuth->user($id)->row();
            $this->data['title'] = "Deactivate User | $app_name - Voice Synthesis Services";
            $this->data['main_page'] = "auth/deactivate_user";
            $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
            $this->data['meta_description'] = "Deactivate $app_name account. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
        } else {
            // do we really want to deactivate?
            if ($this->request->getPost('confirm') === 'yes') {
                // do we have a valid request?
                if ($id !== $this->request->getPost('id', FILTER_VALIDATE_INT)) {
                    throw new \Exception(lang('Auth.error_security'));
                }

                // do we have the right userlevel?
                if ($this->ionAuth->loggedIn() && $this->ionAuth->isAdmin()) {
                    $message = $this->ionAuth->deactivate($id) ? $this->ionAuth->messages() : $this->ionAuth->errors($this->validationListTemplate);
                    $this->session->setFlashdata('message', $message);
                }
            }

            // redirect them back to the auth page
            return redirect()->to('/auth');
        }
    }

    /**
     * Create a new user
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function create_user()
    {

        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        $this->data['title'] = lang('Auth.create_user_heading');

        if ($this->ionAuth->loggedIn()) {
            return redirect()->to('/auth');
        }

        $tables                        = $this->configIonAuth->tables;
        $identityColumn                = $this->configIonAuth->identity;
        $this->data['identity_column'] = $identityColumn;

        // validate form input
        $this->validation->setRule('first_name', 'Enter First Name', 'trim|required');
        $this->validation->setRule('last_name', 'Enter Last Name', 'trim|required');

        $this->validation->setRule('email', 'Enter Email Address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->validation->setRule('phone', 'Enter Mobile Number', 'trim|min_length[6]');
        $this->validation->setRule('password', 'Enter Password', 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[password_confirm]');
        $this->validation->setRule('password_confirm', 'Confirm Password', 'required');

        if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {
            $email    = strtolower($this->request->getPost('email'));
            $identity = $email;
            $password = $this->request->getPost('password');

            $additionalData = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name'  => $this->request->getPost('last_name'),
                'phone'      => $this->request->getPost('phone'),
            ];
        }

        if ($this->request->getPost() && $this->validation->withRequest($this->request)->run() && $this->ionAuth->register($identity, $password, $email, $additionalData)) {
            // check to see if we are creating the user
            // redirect them back to the admin page

            $this->session->setFlashdata('message', $this->ionAuth->messages());
            return redirect()->to('/auth');
        } else {

            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : ($this->ionAuth->errors($this->validationListTemplate) ? $this->ionAuth->errors($this->validationListTemplate) : $this->session->getFlashdata('message'));

            $this->data['title'] = "Registration | $app_name - Voice Synthesis Services";
            $this->data['main_page'] = "register";
            $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
            $this->data['meta_description'] = "Register to $app_name. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
        }
    }

    public function register()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
            $_SESSION['toastMessageType'] = 'error';
            $this->session->markAsFlashdata('toastMessage');
            $this->session->markAsFlashdata('toastMessageType');
            return redirect()->to('admin/users/register_user')->withCookies();
        }

        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        $tables                        = $this->configIonAuth->tables;
        $identityColumn                = $this->configIonAuth->identity;
        $this->data['identity_column'] = $identityColumn;

        // validate form input
        $this->validation->setRule('first_name', 'Enter First Name', 'trim|required');
        $this->validation->setRule('last_name', 'Enter Last Name', 'trim|required');

        $this->validation->setRule('email', 'Enter Email Address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->validation->setRule('phone', 'Enter Mobile Number', 'trim|min_length[6]');
        $this->validation->setRule('password', 'Enter Password', 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[password_confirm]');
        $this->validation->setRule('password_confirm', 'Confirm Password', 'required');

        if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {
            $email    = strtolower($this->request->getPost('email'));
            $identity = $email;
            $password = $this->request->getPost('password');

            $additionalData = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name'  => $this->request->getPost('last_name'),
                'phone'      => $this->request->getPost('phone'),
            ];
        }

        if ($this->request->getPost() && $this->validation->withRequest($this->request)->run() && $this->ionAuth->register($identity, $password, $email, $additionalData)) {
            // check to see if we are creating the user
            // redirect them back to the admin page

            $this->session->setFlashdata('message', $this->ionAuth->messages());
            return redirect()->to('/admin/users');
        } else {

            // display the create user form
            // set the flash data error message if there is one
            $error = $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : ($this->ionAuth->errors($this->validationListTemplate) ? $this->ionAuth->errors($this->validationListTemplate) : $this->session->getFlashdata('message'));
            return redirect()->back()->with('message', $error);
        }
    }

    /**
     * Redirect a user checking if is admin
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function redirectUser()
    {
        if ($this->ionAuth->isAdmin()) {
            return redirect()->to('/auth');
        }
        return redirect()->to('/');
    }

    /**
     * Edit a user
     *
     * @param integer $id User id
     *
     * @return string string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function edit_user(int $id)
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        $this->data['title'] = lang('Auth.edit_user_heading');

        if (!$this->ionAuth->loggedIn() || (!$this->ionAuth->isAdmin() && !($this->ionAuth->user()->row()->id == $id))) {
            return redirect()->to('/auth');
        }

        $user          = $this->ionAuth->user($id)->row();
        $groups        = $this->ionAuth->groups()->resultArray();
        $currentGroups = $this->ionAuth->getUsersGroups($id)->getResult();

        if (!empty($_POST)) {
            // validate form input
            $this->validation->setRule('first_name', lang('Auth.edit_user_validation_fname_label'), 'trim|required');
            $this->validation->setRule('last_name', lang('Auth.edit_user_validation_lname_label'), 'trim|required');
            $this->validation->setRule('phone', lang('Auth.edit_user_validation_phone_label'), 'trim|required');
            $this->validation->setRule('company', lang('Auth.edit_user_validation_company_label'), 'trim|required');

            // do we have a valid request?
            if ($id !== $this->request->getPost('id', FILTER_VALIDATE_INT)) {

                throw new \Exception(lang('Auth.error_security'));
            }

            // update the password if it was posted
            if ($this->request->getPost('password')) {
                $this->validation->setRule('password', lang('Auth.edit_user_validation_password_label'), 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[password_confirm]');
                $this->validation->setRule('password_confirm', lang('Auth.edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {
                $data = [
                    'first_name' => $this->request->getPost('first_name'),
                    'last_name'  => $this->request->getPost('last_name'),
                    'company'    => $this->request->getPost('company'),
                    'phone'      => $this->request->getPost('phone'),
                ];

                // update the password if it was posted
                if ($this->request->getPost('password')) {
                    $data['password'] = $this->request->getPost('password');
                }

                // Only allow updating groups if user is admin
                if ($this->ionAuth->isAdmin()) {
                    // Update the groups user belongs to
                    $groupData = $this->request->getPost('groups');

                    if (!empty($groupData)) {
                        $this->ionAuth->removeFromGroup('', $id);

                        foreach ($groupData as $grp) {
                            $this->ionAuth->addToGroup($grp, $id);
                        }
                    }
                }

                // check to see if we are updating the user
                if ($this->ionAuth->update($user->id, $data)) {
                    $this->session->setFlashdata('message', $this->ionAuth->messages());
                } else {
                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                }
                // redirect them back to the admin page if admin, or to the base url if non admin
                return $this->redirectUser();
            }
        }

        // display the edit user form

        // set the flash data error message if there is one
        $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : ($this->ionAuth->errors($this->validationListTemplate) ? $this->ionAuth->errors($this->validationListTemplate) : $this->session->getFlashdata('message'));

        // pass the user to the view
        $this->data['user']          = $user;
        $this->data['groups']        = $groups;
        $this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = [
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => set_value('first_name', $user->first_name ?: ''),
        ];
        $this->data['last_name'] = [
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'value' => set_value('last_name', $user->last_name ?: ''),
        ];
        $this->data['company'] = [
            'name'  => 'company',
            'id'    => 'company',
            'type'  => 'text',
            'value' => set_value('company', empty($user->company) ? '' : $user->company),
        ];
        $this->data['phone'] = [
            'name'  => 'phone',
            'id'    => 'phone',
            'type'  => 'text',
            'value' => set_value('phone', empty($user->phone) ? '' : $user->phone),
        ];
        $this->data['password'] = [
            'name' => 'password',
            'id'   => 'password',
            'type' => 'password',
        ];
        $this->data['password_confirm'] = [
            'name' => 'password_confirm',
            'id'   => 'password_confirm',
            'type' => 'password',
        ];
        $this->data['ionAuth'] = $this->ionAuth;
        $this->data['title'] = "Edit User | $app_name - Voice Synthesis Services";
        $this->data['main_page'] = "auth/edit_user";
        $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $this->data['meta_description'] = "Edit $app_name account. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";

        return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
    }

    /**
     * Create a new group
     *
     * @return string string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function create_group()
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        $this->data['title'] = lang('Auth.create_group_title');

        if (!$this->ionAuth->loggedIn() || !$this->ionAuth->isAdmin()) {
            return redirect()->to('/auth');
        }

        // validate form input
        $this->validation->setRule('group_name', lang('Auth.create_group_validation_name_label'), 'trim|required|alpha_dash');

        if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {
            $newGroupId = $this->ionAuth->createGroup($this->request->getPost('group_name'), $this->request->getPost('description'));
            if ($newGroupId) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->setFlashdata('message', $this->ionAuth->messages());
                return redirect()->to('/auth');
            }
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : ($this->ionAuth->errors($this->validationListTemplate) ? $this->ionAuth->errors($this->validationListTemplate) : $this->session->getFlashdata('message'));

            $this->data['group_name'] = [
                'name'  => 'group_name',
                'id'    => 'group_name',
                'type'  => 'text',
                'value' => set_value('group_name'),
            ];
            $this->data['description'] = [
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'text',
                'value' => set_value('description'),
            ];
            // render
            $this->data['title'] = "Create new user group | $app_name - Voice Synthesis Services";
            $this->data['main_page'] = "auth/create_group";
            $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
            $this->data['meta_description'] = "Create new group for $app_name account. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
            return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
        }
    }

    /**
     * Edit a group
     *
     * @param integer $id Group id
     *
     * @return string|CodeIgniter\Http\Response
     */
    public function edit_group(int $id = 0)
    {
        $settings = get_settings('general_settings', true);
        $app_name = (isset($settings['company_title']) && $settings['company_title'] != "") ? $settings['company_title'] : "eSpeech";

        // bail if no group id given
        if (!$id) {
            return redirect()->to('/auth');
        }

        $this->data['title'] = lang('Auth.edit_group_title');

        if (!$this->ionAuth->loggedIn() || !$this->ionAuth->isAdmin()) {
            return redirect()->to('/auth');
        }

        $group = $this->ionAuth->group($id)->row();

        // validate form input
        $this->validation->setRule('group_name', lang('Auth.edit_group_validation_name_label'), 'required|alpha_dash');

        if ($this->request->getPost()) {
            if ($this->validation->withRequest($this->request)->run()) {
                $groupUpdate = $this->ionAuth->updateGroup($id, $this->request->getPost('group_name'), ['description' => $this->request->getPost('group_description')]);

                if ($groupUpdate) {
                    $this->session->setFlashdata('message', lang('Auth.edit_group_saved'));
                } else {
                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                }
                return redirect()->to('/auth');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = $this->validation->listErrors($this->validationListTemplate) ?: ($this->ionAuth->errors($this->validationListTemplate) ?: $this->session->getFlashdata('message'));

        // pass the user to the view
        $this->data['group'] = $group;

        $readonly = $this->configIonAuth->adminGroup === $group->name ? 'readonly' : '';

        $this->data['group_name']        = [
            'name'    => 'group_name',
            'id'      => 'group_name',
            'type'    => 'text',
            'value'   => set_value('group_name', $group->name),
            $readonly => $readonly,
        ];
        $this->data['group_description'] = [
            'name'  => 'group_description',
            'id'    => 'group_description',
            'type'  => 'text',
            'value' => set_value('group_description', $group->description),
        ];
        $this->data['title'] = "Edit Group | $app_name - Voice Synthesis Services";
        $this->data['main_page'] = "auth/edit_group";
        $this->data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $this->data['meta_description'] = "Edit $app_name account group. $app_name is one of the leading voice synthesis service provider, that offers text to speech services for over 715+ languages and 80+ voices.";
        return $this->renderPage($this->viewsFolder . DIRECTORY_SEPARATOR . 'template', $this->data);
    }

    /**
     * Render the specified view
     *
     * @param string     $view       The name of the file to load
     * @param array|null $data       An array of key/value pairs to make available within the view.
     * @param boolean    $returnHtml If true return html string
     *
     * @return string|void
     */
    protected function renderPage(string $view, $data = null, bool $returnHtml = true): string
    {
        $viewdata = $data ?: $this->data;

        $viewHtml = view($view, $viewdata);

        if ($returnHtml) {
            return $viewHtml;
        } else {
            echo $viewHtml;
        }
    }
}
