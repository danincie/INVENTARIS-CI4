<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $dataPengguna = [
            [
                'username'   => 'admin',
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('users')->insertBatch($dataPengguna);

        $dataBarang = [
            [
                'nama_barang' => 'Laptop ASUS ROG',
                'kategori'    => 'Elektronik',
                'jumlah'      => 10,
                'harga'       => 15000000.00,
                'deskripsi'   => 'Laptop gaming spesifikasi tinggi',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_barang' => 'Meja Kerja Minimalis',
                'kategori'    => 'Furniture',
                'jumlah'      => 25,
                'harga'       => 850000.00,
                'deskripsi'   => 'Meja kerja kayu jati belanda',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('items')->insertBatch($dataBarang);
    }
}
