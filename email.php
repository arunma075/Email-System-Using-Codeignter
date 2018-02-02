<?php

class Email extends CI_Controller {

public function __construct(){

        parent::__construct();
  	$this->load->helper('url');
      	$this->load->model('email_model');
        $this->load->library('session');

}

public function index(){

$this->load->view("email_reg_view");

}

public function register_user(){
 
      $data=array(
      'uname'=>$this->input->post('uname'),
      'email'=>$this->input->post('email'),
      'paswrd'=>$this->input->post('paswrd'),
        );
 
$email_check=$this->email_model->email_check($data['email']);
 
if($email_check){
  $this->email_model->register_user($data);
  redirect('email/log_view');
 
}
else{
 
  $this->session->set_flashdata('error_msg', 'Error occured,Try again.');
  redirect('email');
 
 
}
 
}

public function log_view(){
 
$this->load->view("email_log_view");
 
}

function login_user(){
  $user_login=array(
 
  'uname'=>$this->input->post('uname'),
  'paswrd'=>($this->input->post('paswrd'))
 
    );
 
    $data=$this->email_model->login_user($user_login['uname'],$user_login['paswrd']);
      if($data)
      {
        $this->session->set_userdata('id',$data['id']);
        $this->session->set_userdata('email',$data['email']);
   
 
        $this->load->view('welcome_email');
 
      }
      else{
        $this->session->set_flashdata('error_msg', 'Error occured,Try again.');
        $this->load->view("email_log_view");
 
      }
 
 
}


public function create_message_view(){
       
           
         
            $this->load->view('welcome_email');
    }
    
    public function compose()
 {
  $save = array(
   'fromaddr' => $this->session->userdata('email'),
    'toaddr'  => $this->input->post('toaddr'),	
    'subject' => $this->input->post('subject'),
    'message' => $this->input->post('message'),       
        );


  $this->email_model->message($save);
  //echo"success";
  redirect('email/create_message_view');

 }
 
public function outbox(){
	     $fromaddr = $this->session->userdata('email');	
        $result['query']=  $this->email_model->send($fromaddr);
        $this->load->view('outbox',$result);
}

public function inbox(){
	     $toaddr = $this->session->userdata('email');	
        $result['query']=  $this->email_model->inbox($toaddr);
        $this->load->view('inbox',$result);
}
public function send_delete($id) {

        $this->load->model("email_model");
        $this->email_model->send_delete($id);
        redirect('mail/outbox');
    }
 public function inbox_delete($id) {

        $this->load->model("email_model");
        $this->mail_model->inbox_delete($id);
        redirect('mail/inbox');
    }
    
    public function user_logout(){
 
  $this->session->sess_destroy();
  redirect('email/log_view', 'refresh');
}
 
function home()
	{
		$this->load->view('welcome_email.php');
	}
    
}
    


?>