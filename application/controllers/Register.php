<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users');
        $this->load->library(array('form_validation', 'session'));
        $this->load->helper(array('url', 'html', 'form'));
        $this->user_id = $this->session->userdata('user_id');
    }

    /**
     * Default function for this controller
     *
     * @return void
     */
    public function index()
    {
        $this->load->view('register/register');
    }

    /**
     * initialize registration & login page
     *
     * @return void
     */
    public function registration()
    {
        $this->setRegisterRule();
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['errors' => validation_errors(), 'status' => false]);
        } else {
            $this->addNewUser();
        }
    }

    /**
     * Setting rules for Registration form
     *
     * @return void
     */
    private function setRegisterRule()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_message('required', 'Enter %s');
        $this->form_validation->set_message('is_unique', '%s is taken.');

    }

    /**
     * Checking Email on Key Press
     *
     * @return void
     */
    public function checkEmailOnTheFly()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['message' => 'invalid', 'status' => false]);
            exit;
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[users.email]');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['message' => 'unavailable', 'status' => false]);
            exit;
        }
        echo json_encode(['message' => 'available', 'status' => true]);
        exit;
    }

    /**
     * Adding new User after validation
     *
     * @return void
     */
    private function addNewUser()
    {
        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password')),
        );

        $id_user = $this->Users->addUser($data);

        if ($id_user != false) {
            $user = array(
                'user_id' => $id_user,
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
            );
        }

        $this->session->set_userdata($user);
        $full_name = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
        echo json_encode(['status' => true, 'message' => "Congrates $full_name! You're in! Click on the LogIn tab to login."]);
    }

    /**
     * Initialize login form
     *
     * @return void
     */
    public function login()
    {
        $this->setLoginRule();
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['errors' => validation_errors(), 'status' => false]);
        } else {
            $this->loginSuccess();
        }
    }

    /**
     * Success message for Login
     *
     * @return void
     */
    private function loginSuccess()
    {
        echo json_encode(['status' => true, 'message' => "Congrates! You're Loggedin!"]);
    }

    /**
     * Initialize Login from validation rules
     *
     * @return void
     */
    private function setLoginRule()
    {
        if (empty($this->input->post('email')) || empty($this->input->post('password'))) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_authenticate');
        }

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_message('required', 'Enter %s');
    }

    /**
     * Validate Login credentials (callback function from callback_authenticate validation rule)
     *
     * @return void
     */
    public function authenticate()
    {
        $credentials = array(
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password')),
        );
        $user = $this->Users->authenticate($credentials);
        if ($user) {
            $this->setUserSession($user);
            return true;
        }
        $this->form_validation->set_message(__FUNCTION__, 'Email or password doesn\'t match.');
        return false;
    }

    /**
     * Set User data in session
     *
     * @param [type] $user
     * @return void
     */
    private function setUserSession($user)
    {
        $user = array(
            'user_id' => $user->id,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name
        );

        $this->session->set_userdata($user);
    }

    /**
     * Logout and unset User data
     *
     * @return void
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/register/');
    }
}
