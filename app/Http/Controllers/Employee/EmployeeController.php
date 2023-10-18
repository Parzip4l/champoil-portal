<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\ModelCG\Jabatan;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $karyawan = Employee::all();
        return view('pages.hc.karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $jabatan = Jabatan::all();
        return view('pages.hc.karyawan.create', compact('jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'nama' => 'required',
                'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        
            $data = new Employee();
            $data->ktp = $request->ktp;
            $data->nik = $request->nik;
            $data->nama = $request->nama;
            $data->alamat = $request->alamat;
            $data->jabatan = $request->jabatan;
            $data->organisasi = $request->organisasi;
            $data->status_kontrak = $request->status_kontrak;
            $data->joindate = $request->joindate;
            $data->berakhirkontrak = $request->berakhirkontrak;
            $data->email = $request->email;
            $data->telepon = $request->telepon;
            $data->status_pernikahan = $request->status_pernikahan;
            $data->agama = $request->agama;
            $data->tanggal_lahir = $request->tanggal_lahir;
            $data->tempat_lahir = $request->tempat_lahir;
            $data->jenis_kelamin = $request->jenis_kelamin;

            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $filename);
                $data->gambar = $filename;
            }
            $data->save();

            return redirect()->route('employee.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }catch (ValidationException $exception) {
            $errorMessage = $exception->validator->errors()->first(); // ambil pesan error pertama dari validator
            redirect()->route('employee.index')->with('error', 'Gagal menyimpan data. ' . $errorMessage); // tambahkan alert error
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        return view('pages.hc.karyawan.details', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        return view('pages.hc.karyawan.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ktp' => 'required|numeric',
            'nik' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'agama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-Laki,Perempuan',
            'email' => 'required|email',
            'telepon' => 'required|string|max:15',
            'status_kontrak' => 'required|string|in:Contract,Permanent',
            'organisasi' => 'required|string|in:Professional Frontline,Management Leaders',
            'joindate' => 'required|date',
            'berakhirkontrak' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'status_pernikahan' => 'required|string|in:Married,Single',
        ]);

        // Find the employee by ID
        $employee = Employee::findOrFail($id);

        // Update the employee data
        $employee->nama = $request->input('nama');
        $employee->ktp = $request->input('ktp');
        $employee->nik = $request->input('nik');
        $employee->jabatan = $request->input('jabatan');
        $employee->agama = $request->input('agama');
        $employee->jenis_kelamin = $request->input('jenis_kelamin');
        $employee->email = $request->input('email');
        $employee->telepon = $request->input('telepon');
        $employee->status_kontrak = $request->input('status_kontrak');
        $employee->organisasi = $request->input('organisasi');
        $employee->joindate = $request->input('joindate');
        $employee->berakhirkontrak = $request->input('berakhirkontrak');
        $employee->tempat_lahir = $request->input('tempat_lahir');
        $employee->tanggal_lahir = $request->input('tanggal_lahir');
        $employee->alamat = $request->input('alamat');
        $employee->status_pernikahan = $request->input('status_pernikahan');

        // Save the updated employee
        $employee->save();

        // Redirect to a view or return a response as needed
        return redirect()->route('employee.index')->with('success', 'Employee data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Employee::find($id);
        $contact->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Successfully Deleted');
    }
}
