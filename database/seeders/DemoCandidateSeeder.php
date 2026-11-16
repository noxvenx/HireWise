<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoCandidateSeeder extends Seeder
{
    public function run(): void
    {
        $candidates = [
            ['Andi Pratama', 'male', 'S1', 'Universitas Pamulang', 'Teknik Informatika'],
            ['Siti Nurhaliza', 'female', 'S1', 'Universitas Budi Luhur', 'Sistem Informasi'],
            ['Muhammad Fajar', 'male', 'S1', 'Universitas Mercu Buana', 'Manajemen'],
            ['Dewi Anggraini', 'female', 'S1', 'Universitas Pamulang', 'Akuntansi'],
            ['Rizky Ramadhan', 'male', 'D3', 'Politeknik Negeri Jakarta', 'Administrasi Bisnis'],
            ['Putri Amelia', 'female', 'S1', 'Universitas Indonesia', 'Psikologi'],
            ['Aditya Saputra', 'male', 'S1', 'Universitas Gunadarma', 'Teknik Informatika'],
            ['Nadia Safitri', 'female', 'S1', 'Universitas Pamulang', 'Manajemen'],
            ['Bima Arya', 'male', 'D3', 'Politeknik Negeri Jakarta', 'Akuntansi'],
            ['Aulia Rahmawati', 'female', 'S1', 'Universitas Mercu Buana', 'Sistem Informasi'],
            ['Dimas Kurniawan', 'male', 'S1', 'Universitas Gunadarma', 'Teknik Informatika'],
            ['Intan Permatasari', 'female', 'S1', 'Universitas Budi Luhur', 'Ilmu Komunikasi'],
            ['Rafi Maulana', 'male', 'S1', 'Universitas Pamulang', 'Manajemen'],
            ['Citra Lestari', 'female', 'D3', 'Politeknik Negeri Jakarta', 'Administrasi Bisnis'],
            ['Farhan Akbar', 'male', 'S1', 'Universitas Indonesia', 'Teknik Industri'],
            ['Maya Salsabila', 'female', 'S1', 'Universitas Gunadarma', 'Akuntansi'],
            ['Yoga Firmansyah', 'male', 'S1', 'Universitas Mercu Buana', 'Teknik Informatika'],
            ['Anisa Fitriani', 'female', 'S1', 'Universitas Pamulang', 'Psikologi'],
            ['Ilham Setiawan', 'male', 'D3', 'Politeknik Negeri Jakarta', 'Manajemen Informatika'],
            ['Vina Oktaviani', 'female', 'S1', 'Universitas Budi Luhur', 'Sistem Informasi'],
            ['Bagas Prakoso', 'male', 'S1', 'Universitas Gunadarma', 'Manajemen'],
            ['Rani Maharani', 'female', 'S1', 'Universitas Indonesia', 'Akuntansi'],
            ['Kevin Wijaya', 'male', 'S1', 'Universitas Mercu Buana', 'Teknik Informatika'],
            ['Desi Wulandari', 'female', 'D3', 'Politeknik Negeri Jakarta', 'Administrasi Bisnis'],
            ['Arif Hidayat', 'male', 'S1', 'Universitas Pamulang', 'Sistem Informasi'],
            ['Laras Puspita', 'female', 'S1', 'Universitas Gunadarma', 'Psikologi'],
            ['Reza Firmansyah', 'male', 'S1', 'Universitas Budi Luhur', 'Teknik Informatika'],
            ['Nabila Zahra', 'female', 'S1', 'Universitas Mercu Buana', 'Manajemen'],
            ['Fikri Ramadhan', 'male', 'D3', 'Politeknik Negeri Jakarta', 'Akuntansi'],
            ['Dinda Aprilia', 'female', 'S1', 'Universitas Pamulang', 'Ilmu Komunikasi'],
        ];

        DB::transaction(function () use ($candidates) {
            foreach ($candidates as $index => $candidate) {
                $number = $index + 1;

                $code = 'HW-CND-9' . str_pad(
                    (string) $number,
                    5,
                    '0',
                    STR_PAD_LEFT
                );

                DB::table('candidates')->updateOrInsert(
                    ['candidate_code' => $code],
                    [
                        'full_name' => $candidate[0],
                        'email' => 'kandidat' . $number . '@example.test',
                        'phone' => '08000000' . str_pad(
                            (string) $number,
                            4,
                            '0',
                            STR_PAD_LEFT
                        ),
                        'gender' => $candidate[1],
                        'birth_date' => sprintf(
                            '%04d-%02d-%02d',
                            1995 + ($number % 7),
                            1 + ($number % 12),
                            1 + ($number % 27)
                        ),
                        'address' => 'Tangerang Selatan, Banten',
                        'education' => $candidate[2],
                        'institution' => $candidate[3],
                        'major' => $candidate[4],
                        'graduation_year' => 2018 + ($number % 9),
                        'notes' => 'Data dummy presentasi HireWise',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });
    }
}
