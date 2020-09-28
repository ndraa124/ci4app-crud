<?php

namespace App\Controllers;

use App\Models\MobilModel;

class Home extends BaseController
{
	protected $model;

	public function __construct()
	{
		$this->model = new MobilModel();
	}

	public function index()
	{
		$data = [
			'content' => 'home'
		];
		return view('layout/template', $data);
	}

	public function ajaxList()
	{
		$draw = $_REQUEST['draw'];
		$length = $_REQUEST['length'];
		$start = $_REQUEST['start'];
		$search = $_REQUEST['search']['value'];

		$total = $this->model->ajaxGetTotal();
		$output = [
			'length' => $length,
			'draw' => $draw,
			'recordsTotal' => $total,
			'recordsFiltered' => $total
		];

		if ($search !== "") {
			$list = $this->model->ajaxGetDataSearch($search, $start, $length);
		} else {
			$list = $this->model->ajaxGetData($start, $length);
		}

		if ($search !== "") {
			$total_search = $this->model->ajaxGetTotalSearch($search);
			$output = [
				'recordsTotal' => $total_search,
				'recordsFiltered' => $total_search
			];
		}

		$data = [];
		$no = $start + 1;
		foreach ($list as $temp) {
			$aksi = '
				<a href="javascript:void(0)" class="btn btn-warning" onclick="editData(' . $temp['id'] . ')">Edit</a>
				<a href="javascript:void(0)" class="btn btn-danger" onclick="hapusData(' . $temp['id'] . ')">Hapus</a>
			';

			$row = [];
			$row[] = $no;
			$row[] = '<img src="' . base_url() . '/uploads/' . $temp['gambar'] . '" width="100px">';
			$row[] = $temp['merk'];
			$row[] = $temp['plat'];
			$row[] = $aksi;

			$data[] = $row;
			$no++;
		}

		$output['data'] = $data;

		echo json_encode($output);
		exit();
	}

	public function simpan()
	{
		//Validasi
		$this->_validate('save');

		//File Gambar
		$gambar = $this->request->getFile('gambar');
		$gambar->move('uploads');

		$data = [
			'merk' => $this->request->getVar('merk'),
			'plat' => $this->request->getVar('plat'),
			'gambar' => $gambar->getName(),
		];

		if ($this->model->save($data)) {
			echo json_encode(['status' => TRUE]);
		} else {
			echo json_encode(['status' => FALSE]);
		}
	}

	public function edit($id)
	{
		$data = $this->model->find($id);
		echo json_encode($data);
	}

	public function update()
	{
		//Validasi
		$this->_validate('update');

		$id = $this->request->getVar('id');
		$mobil = $this->model->find($id);

		if ($this->request->getFile('gambar') == '') {
			$gambarName = $mobil['gambar'];
		} else {
			//Hapus Gambar Sebelumnya
			unlink('uploads/' . $mobil['gambar']);

			//File Gambar
			$gambar = $this->request->getFile('gambar');
			$gambar->move('uploads');
			$gambarName = $gambar->getName();
		}

		$data = [
			'id' => $id,
			'merk' => $this->request->getVar('merk'),
			'plat' => $this->request->getVar('plat'),
			'gambar' => $gambarName,
		];

		if ($this->model->save($data)) {
			echo json_encode(['status' => TRUE]);
		} else {
			echo json_encode(['status' => FALSE]);
		}
	}

	public function delete($id)
	{
		$mobil = $this->model->find($id);

		//Hapus Gambar Sebelumnya
		if ($mobil['gambar']) {
			unlink('uploads/' . $mobil['gambar']);
		}

		if ($this->model->delete($id)) {
			echo json_encode(['status' => TRUE]);
		} else {
			echo json_encode(['status' => FALSE]);
		}
	}

	public function _validate($method)
	{
		if (!$this->validate($this->model->getRulesValidation($method))) {
			$validation = \Config\Services::validation();

			$data = [];
			$data['error_string'] = [];
			$data['inputerror'] = [];
			$data['status'] = TRUE;

			if ($validation->hasError('merk')) {
				$data['inputerror'][] = 'merk';
				$data['error_string'][] = $validation->getError('merk');
				$data['status'] = FALSE;
			}

			if ($validation->hasError('plat')) {
				$data['inputerror'][] = 'plat';
				$data['error_string'][] = $validation->getError('plat');
				$data['status'] = FALSE;
			}

			if ($validation->hasError('gambar')) {
				$data['inputerror'][] = 'gambar';
				$data['error_string'][] = $validation->getError('gambar');
				$data['status'] = FALSE;
			}

			if ($data['status'] === FALSE) {
				echo json_encode($data);
				exit();
			}
		}
	}
}
