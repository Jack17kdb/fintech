<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController {
    protected $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function ProfileForm() {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Not Authorized');
        }

        $user = $this->userModel->find($userId);

        return view('wallet/profile', [
            'name'     => $user['name'],
            'email'    => $user['email'],
            'status'   => $user['status'],
            'location' => $user['location'] ?? ''
        ]);
    }

    public function InfoChange() {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return redirect()->back()->with('error', 'Not Authorized');
        }

        $data = [];
        $newPassword = $this->request->getPost('password');
        $newLocation = $this->request->getPost('location');

        if (!empty($newPassword)) {
            if (strlen($newPassword) < 8) {
                return redirect()->back()->with('error', 'Password must be at least 8 characters.');
            }
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if (!empty($newLocation)) {
            $data['location'] = $newLocation;
        }

        if (empty($data)) {
            return redirect()->back()->with('info', 'No changes were made.');
        }

        if ($this->userModel->update($userId, $data)) {
            return redirect()->back()->with('success', 'Profile updated successfully');
        }

        return redirect()->back()->with('error', 'Update failed');
    }
}
