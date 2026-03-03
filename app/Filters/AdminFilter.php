<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface{
	public function before(RequestInterface $request, $arguments=null){
		if(session()->get('role') != 'admin'){
			return redirect()->to('/wallet')->with('error', 'Not Authorized');
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){}
}
