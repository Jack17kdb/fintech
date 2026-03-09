<?php

namespace App\Controllers;

use App\Models\FeeRuleModel;

class PriceController extends BaseController{
	protected $feeRuleModel;

	public function __construct(){
		$this->feeRuleModel = new FeeRuleModel();
	}

	public function FeeRuleForm(){
		$rules = $this->feeRuleModel->orderBy('id', 'DESC')->paginate(10);

		$kenya_locations = [
			'Baringo', 'Bomet', 'Bungoma', 'Busia', 'Elgeyo-Marakwet',
			'Embu', 'Garissa', 'Homa Bay', 'Isiolo', 'Kajiado',
			'Kakamega', 'Kericho', 'Kiambu', 'Kilifi', 'Kirinyaga',
			'Kisii', 'Kisumu', 'Kitui', 'Kwale', 'Laikipia',
			'Lamu', 'Machakos', 'Makueni', 'Mandera', 'Marsabit',
			'Meru', 'Migori', 'Mombasa', 'Muranga', 'Nairobi',
			'Nakuru', 'Nandi', 'Narok', 'Nyamira', 'Nyandarua',
			'Nyeri', 'Samburu', 'Siaya', 'Taita-Taveta', 'Tana River',
			'Tharaka-Nithi', 'Trans-Nzoia', 'Turkana', 'Uasin Gishu',
			'Vihiga', 'Wajir', 'West Pokot'
		];
		sort($kenya_locations);

		return view('admin/feerules', [
			'rules'           => $rules,
			'pager'           => $this->feeRuleModel->pager,
			'kenya_locations' => $kenya_locations,
		]);
	}

	public function addFeeRule(){
		$data = [
			'transaction_type' => $this->request->getPost('transaction_type'),
			'location' => $this->request->getPost('location'),
			'fixed_fee' => $this->request->getPost('fixed_fee')
		];

		if (empty($data['transaction_type']) || empty($data['location']) || empty($data['fixed_fee'])) {
        	    return redirect()->back()->with('error', 'Please fill in all required fields.');
	        }

		if ($this->feeRuleModel->insert($data)) {
	            return redirect()->back()->with('success', 'Fee rule added successfully.');
	        }

		return redirect()->back()->with('error', 'Failed to save the fee rule.');
	}
}
