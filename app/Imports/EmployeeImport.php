<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Employee;
use App\User;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        
        try {
            // Start a database transaction
            DB::beginTransaction();

            foreach ($rows as $row) {
                $unit_bisnis = $company->unit_bisnis;
                $useraccess = ["dashboard_access"];
                $hashedPassword = Hash::make($row['password']);

                // Create Employee
                $employee = Employee::create([
                    'nama' => $row['nama'],
                    'ktp' => $row['ktp'],
                    'nik' => $row['nik'],
                    'referal_code' => $row['referal_code'],
                    'divisi' => $row['divisi'],
                    'jabatan' => $row['jabatan'],
                    'agama' => $row['agama'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'email' => $row['email'],
                    // ... (other columns)
                    'expired_sertifikasi' => $row['expired_sertifikasi'],
                ]);

                // Create User with hashed password and encoded permissions
                $user = User::create([
                    'name' => $row['nik'],
                    'email' => $row['email'],
                    'password' => $hashedPassword,
                    'permission' => $useraccess,
                    'employee_code' => $row['nik'],
                    'company' => $unit_bisnis,
                ]);

                // If both operations are successful, you can perform additional actions here.
                // Example: Send a notification, log the success, etc.
            }

            // Commit the transaction if all operations are successful
            DB::commit();

        } catch (\Exception $e) {
            // Handle any exceptions or errors that may occur during the creation.
            // You can log the error, send a notification, or perform any other necessary action.
            
            // Optionally, you can roll back the database transaction if needed.
            DB::rollBack();

            // Rethrow the exception to let it propagate further if needed.
            throw $e;
        }
    }
}
