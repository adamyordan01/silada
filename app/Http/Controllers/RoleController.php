<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return view('roles.index');
    }

    public function getRoles()
    {
        $roles = Role::select(['id', 'name']);
        return datatables()->of($roles)
            ->addIndexColumn()
            ->addColumn('action', function ($roles) {
                $role = Auth::user()->role->name;

                if ($role == 'admin') {
                    $buttonEdit = '<button data-toggle="tooltip" data-placement="top" title="Edit Produk" data-id="'.$roles->id.'" id="editButton" class="btn btn-info mr-2"><i class="fas fa-edit"></i></button>';
                    // $buttonDelete = '<button data-toggle="tooltip" data-placement="top" title="Delete Produk" data-id="'.$role->id.'" id="deleteButton" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>';
                } else {
                    $buttonEdit = '';
                }

                return $buttonEdit;
            })
            ->editColumn('created_at', function ($role) {
                return Carbon::parse($role->created_at)->format('d-m-Y');
            })
            ->editColumn('updated_at', function ($role) {
                return Carbon::parse($role->updated_at)->format('d-m-Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.max' => 'Nama Role maksimal 255 karakter',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray(),
            ]);
        } else {
            $role = Role::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            if ($role) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Role berhasil ditambahkan',
                ]);
            } else {
                return response()->json([
                    'code' => 0,
                    'message' => 'Role gagal ditambahkan',
                ]);
            }
        }
    }

    public function getRoleDetail(Request $request)
    {
        $role_id = $request->role_id;

        $role = Role::find($role_id);
        return response()->json([
            'role' => $role
        ]);
    }

    public function update(Request $request)
    {
        $role_id = $request->role_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.max' => 'Nama Role maksimal 255 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray(),
            ]);
        } else {
            $role = Role::find($role_id);
            $role->name = $request->name;

            $query = $role->save();

            if ($query) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Role berhasil diubah',
                ]);
            } else {
                return response()->json([
                    'code' => 0,
                    'message' => 'Role gagal diubah',
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        $role_id = $request->role_id;

        $role = Role::find($role_id);
        $query = $role->delete();

        if ($query) {
            return response()->json([
                'code' => 1,
                'message' => 'Role berhasil dihapus',
            ]);
        } else {
            return response()->json([
                'code' => 0,
                'message' => 'Role gagal dihapus',
            ]);
        }
    }
}
