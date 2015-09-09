<?php
/**
* 
*/
class Auth extends MY_Controller
{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth_m');
	}

	function index($error = '')
	{
		// $this->logout();
		$data['error'] = '';
		$this->load->view('auth_v2',$data);
	}

	function login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$hashed = $this->encrypt($password);
		// echo $hashed;
		$authentication = $this->auth_m->getUser($email, $hashed);
		// echo "<pre>";print_r($authentication);die();
		if($authentication['auth'] == TRUE)
		{
			$user_id = $authentication['user_id'];
			$user_details = $this->get_user($user_id);
			$user_table = $user_details[0]['user_type'];
			
			$data = array(
				'logged_in' => TRUE,
				'userid' => $user_id,
				'user_table' => $user_table
			);

			// echo "<pre>";print_r($user_details);echo "</pre>";exit;

			$this->session->set_userdata($data);

			$redirect_url = $user_details[0]['user_type'];

			// $redirect_url = $this->getRedirect($user_type, $user_id);
			// // echo $redirect_url;die();
			
			// echo "<pre>";print_r($this->session->all_userdata());die;
			// // echo base_url() . $redirect_url;die();
			redirect(base_url() . $redirect_url);
		}
		else
		{
			// $this->load->view('auth_v2', $data);
			redirect(base_url().'auth/login_message/1/error');
		}
	}	

	function login_message($message = NULL,$type = NULL)
	{
		// $this->logout();
		if (isset($message)&&$type == 'error') {
			$info = '</br><b><i class = "fa fa-exclamation-circle"></i> Login Error! Wrong Username or Password</b></br>';
		}elseif (isset($message)&&$type == 'registration') {
			$info = '</br><b><i class="fa fa-info-circle"></i> You have been successfully registered.<i class="fa fa-info-circle"></i></br>Proceed to Login </b></br>';
		}

		$data['message'] = $info;
		// echo "<pre>";print_r($info);exit;
		$this->load->view('auth_v2',$data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url().'auth');
	}
}
?>