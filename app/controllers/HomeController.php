<?php 

class HomeController extends Controller
{
	protected $model;
	
	public function __construct()
	{
	    $this->model = $this->model('User');
	}

	public function index()
	{
		var_dump($this->model->get());

		// $this->views('home', $data);
	}

}
