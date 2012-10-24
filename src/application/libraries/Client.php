<?php
	
	
class Client {
    
    private $controller;

    function __construct($controller)
    {
        $this->controller = $controller;
    }
    
    public function isAuth()
    {
        return $this->controller[0]->session->userdata('status') == "OK";
    }
	
	public function checkAuth() {
		if (!isAuth()) {
			redirect("signup", "refresh");
		}
	}
    
    public function loadFooter()
    {
        $this->controller[0]->load->view("footer.php");
    }
    
    public function loadHeader($title)
    {

        $this->controller[0]->load->library('parser');
        $data = array(
            "title" => $title." - ".$this->controller[0]->config->item("system_name"),
			"userControl" => ""
        );
		
		if ($this->isAuth()) {
			$data["userControl"] = '
			<div class="btn-group pull-right">
			  <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog icon-white"></i></a>
			  <ul class="dropdown-menu inverse">
				<li><a href="#">Hello '.$this->controller[0]->session->userdata('name').'!</a></li>
				<li class="divider"></li>
				<li><a href="/tickets"><i class="icon-tags"></i> My Tickets</a></li>
				<li><a href="/account"><i class="icon-user"></i> View Account</a></li>
				<li class="divider"></li>
				<li><a href="/help"><i class="icon-question-sign"></i> Help</a></li>
				<li class="divider"></li>
				<li><a href="/logout"><i class="icon-off"></i> Sign out</a></li>
			  </ul>
			</div>
			';
		} else {
			$data["userControl"] = '
			<form class="navbar-form pull-right" action="/login" method="post" accept-charset="utf-8">
              <input class="span2" type="text" placeholder="Email" name="login_email">
              <input class="span2" type="password" placeholder="Password" name="login_password">
              <button type="submit" class="btn">Sign in</button>
            </form>
			';
		}
            
        $this->controller[0]->parser->parse("header.php", $data);
    }
}
	