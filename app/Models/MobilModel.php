<?php

namespace App\Models;

use CodeIgniter\Model;

class MobilModel extends Model
{
    protected $table = 'mobil';
    protected $useTimestamps = true;
    protected $allowedFields = ['merk', 'plat', 'gambar'];

    public function getRulesValidation($method = null)
    {
        if ($method == 'save') {
            $imgRules = 'uploaded[gambar]|max_size[gambar, 1024]|is_image[gambar]|ext_in[gambar,png,jpg]';
            $platRules = 'required|is_unique[mobil.plat]';
        } else {
            $imgRules = 'max_size[gambar, 1024]|is_image[gambar]|ext_in[gambar,png,jpg]';
            $platRules = 'required';
        }

        $rulesValidation = [
            'merk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'plat' => [
                'rules' => $platRules,
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'is_unique' => '{field} sudah digunakan.'
                ]
            ],
            'gambar' => [
                'rules' => $imgRules,
                'errors' => [
                    'uploaded' => '{field} harus diisi.',
                    'max_size' => '{field} melebihi ukuran yang ditentukan',
                    'is_image' => 'format {field} tidak sesuai.',
                    'ext_in' => 'hanya format JPG, PNG yang diijinkan.'
                ]
            ]
        ];

        return $rulesValidation;
    }

    public function ajaxGetData($start, $length)
    {
        $result = $this->orderBy('merk', 'asc')
            ->findAll($start, $length);

        return $result;
    }

    public function ajaxGetDataSearch($search, $start, $length)
    {
        $result = $this->like('merk', $search)
            ->orLike('plat', $search)
            ->findAll($start, $length);

        return $result;
    }

    public function ajaxGetTotal()
    {
        $result = $this->countAll();

        if (isset($result)) {
            return $result;
        }

        return 0;
    }

    public function ajaxGetTotalSearch($search)
    {
        $result = $this->like('merk', $search)
            ->orLike('plat', $search)
            ->countAllResult();

        return $result;
    }
}
