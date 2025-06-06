<?php

namespace App\Http\Controllers;

use App\User;
use App\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $users = User::where('company', $company->unit_bisnis)->get();
        $employee = Employee::where('unit_bisnis', $company->unit_bisnis)->get();
        return view('pages.app-setting.user.index', compact('users','employee'));
    }

    public function create()
    {   
        return view('pages.app-setting.user.create');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->input('term');
        $users = Employee::select('id','nik','nama')
            ->where('nama', 'LIKE', '%' . $term . '%')
            ->get();
        
        $response = array();
        foreach($users as $user){
            $response[] = array(
                'id' => $user->id,
                'nik' => $user->nik,
                'value' => $user->nama
            );
        }
        
        return response()->json($response);
    }

    public function show($id)
    {
        
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'permissions' => 'required|array',
        ]);
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->project_id = $request->project_id;
        $user->employee_code = $request->name;
        $user->password = Hash::make($request->password);
        $user->permission = json_encode($request->permissions);
        $user->company = $company->unit_bisnis;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $user = User::where('employee_code', $id)->firstOrFail();
    
            // Validasi bahwa password sebelumnya cocok
            if (!\Hash::check($request->input('current_password'), $user->password)) {
                return redirect()->back()->with('error', 'Password sebelumnya tidak cocok.');
            }
    
            // Validasi bahwa password baru cocok dengan konfirmasi
            if ($request->input('password') !== $request->input('password_confirmation')) {
                return redirect()->back()->with('error', 'Password baru dan konfirmasi password tidak cocok.');
            }
    
            $user->update([
                'password' => bcrypt($request->input('password')),
            ]);
    
            return redirect()->back()->with('success', 'Password Berhasil Diupdate.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    public function ResetPassword(Request $request, $id)
    {
        try {
            $user = User::where('employee_code', $id)->firstOrFail();
    
            $user->update([
                'password' => bcrypt($request->input('password')),
            ]);
    
            return redirect()->back()->with('success', 'Password Berhasil Diupdate.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'boolean',
        ]);

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
