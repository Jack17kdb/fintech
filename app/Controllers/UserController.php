<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController{
	protected $userModel;

	public function __construct(){
		$this->userModel = new UserModel();
	}

	public function UserListForm(){
		$data = [
        	    'users' => $this->userModel->orderBy('created_at', 'DESC')->paginate(10),
        	    'pager' => $this->userModel->pager,
        	];

	        return view('admin/users', $data);
	}

	public function DeleteUser($id = null) {
	    if ($id === null) {
		return redirect()->back()->with('error', 'No user ID provided.');
	    }

	    if (!$this->userModel->find($id)) {
		return redirect()->back()->with('error', 'User not found.');
	    }

	    if ($this->userModel->delete($id)) {
		return redirect()->back()->with('success', 'User deleted successfully.');
	    }

	    return redirect()->back()->with('error', 'Could not delete user.');
	}
}
