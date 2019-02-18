<?php
namespace app\api\controller;

class Test
{
	public function index()
	{
		return $this->request->post();
	}
}