<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::select(['id', 'name', 'slug'])->get();
        $positions = Position::select(['id', 'name'])->get();
        return view('users.index', [
            'roles' => $roles,
            'positions' => $positions,
        ]);
    }

    public function getUSers()
    {
        $role = Auth::user()->role->name;
        // make query get users relation to roles
        // $users = User;
        $users = DB::select("
            SELECT
                users.id,
                users.`name`,
                users.username,
                users.email,
                users.status,
                users.created_at,
                users.updated_at,
                roles.`name` AS role,
                positions.`name` AS position
            FROM
                users
                INNER JOIN roles ON users.role_id = roles.id
                INNER JOIN positions ON users.position_id = positions.id
        ");

        return datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($user) {
                $role = Auth::user()->role->name;
                // $buttonEdit = '<button data-toggle="tooltip" data-placement="top" title="Edit Produk" data-id="'.$user->id.'" id="editButton" class="btn btn-info mr-2"><i class="fas fa-edit"></i></button>';
                // $buttonDelete = '<button data-toggle="tooltip" data-placement="top" title="Delete Produk" data-id="'.$user->id.'" id="deleteButton" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>';
                if ($role == 'admin') {
                    $buttonEdit = '<a href="/user/edit/'.$user->id.'" class="btn btn-info"><i class="fas fa-edit"></i></a>';
                } else {
                    $buttonEdit = '';
                }
                return $buttonEdit;
            })
            ->editColumn('created_at', function ($user) {
                return Carbon::parse($user->created_at)->format('d-m-Y');
            })
            ->editColumn('updated_at', function ($user) {
                return Carbon::parse($user->updated_at)->format('d-m-Y');
            })
            ->addColumn('status', function ($user) {
                if ($user->status == 1) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-dark">Tidak Aktif</span>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function getUserDetail(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        return response()->json([
            'detail' => $user
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'position' => 'required',
        ], [
            'name.required' => 'Nama User harus diisi',
            'name.max' => 'Nama User maksimal 255 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role harus diisi',
            'position.required' => 'Position harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role_id = $request->role;
            $user->position_id = $request->position;
            $user->status = 1;
            $user->save();
            return response()->json([
                'code' => 1,
                'message' => 'Pengguna berhasil ditambahkan'
            ]);
        }
    }

    public function edit(User $user)
    {
        $roles = Role::select(['id', 'name', 'slug'])->get();
        return view('users.edit', [
            'roles' => $roles,
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // dd($request->all());
        if ($request->password) {
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role,
                'status' => $request->status
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role_id' => $request->role,
                'status' => $request->status
            ]);
        }
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diubah');
    }
}
