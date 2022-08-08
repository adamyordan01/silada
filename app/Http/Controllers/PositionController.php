<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function index()
    {
        return view('positions.index');
    }

    public function getPosition()
    {
        $positions = Position::select(['id', 'name', 'created_at', 'updated_at']);

        return DataTables::of($positions)
        ->addIndexColumn()
        ->editColumn('created_at', function ($position) {
            return Carbon::parse($position->created_at)->format('d-m-Y');
        })
        ->editColumn('updated_at', function ($position) {
            return Carbon::parse($position->updated_at)->format('d-m-Y');
        })
        ->editColumn('action', function ($position) {
            $edit = url('process/kgb/show/'.$position->id);
            $delete = url('process/kgb/process/'.$position->id);
            $role = Auth::user()->role->name;
            
            if ($role == 'admin') {
                $buttonEdit = '<button data-toggle="tooltip" data-placement="top" title="Edit Jabatan" data-id="'.$position->id.'" id="editButton" class="btn btn-info"><i class="fas fa-edit"></i></button>';
                $buttonDelete = '<button data-toggle="tooltip" data-placement="top" title="Delete Jabatan" data-id="'.$position->id.'" id="deleteButton" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>';
            } else {
                $buttonEdit = '';
            }
            return $buttonEdit;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:positions,name',
        ], [
            'name.required' => 'Nama Jabatan tidak boleh kosong',
            'name.unique' => 'Nama Jabatan sudah ada',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $position = new Position();
            $position->name = $request->name;
            $query = $position->save();

            if (!$query) {
                return response()->json(['code' => 0, 'message' => 'Maaf sedang terjadi masalah.']);
            } else {
                return response()->json(['code' => 1, 'message' => 'Jabatan Berhasil ditambahkan.']);
            }
        }
    }

    public function getPositionDetail(Request $request)
    {
        $position_id = $request->position_id;
        $positionDetails = Position::find($position_id);
        return response()->json(['details' => $positionDetails]);
    }

    public function update(Request $request)
    {
        $position_id = $request->position_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required',Rule::unique('positions')->ignore($position_id),
        ], [
            'name.required' => 'Nama Jabatan tidak boleh kosong',
            'name.unique' => 'Nama Jabatan sudah ada',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $position = Position::find( $position_id);
            $position->name = $request->name;
            $query = $position->save();

            if (!$query) {
                return response()->json(['code' => 0, 'message' => 'Maaf sedang terjadi masalah.']);
            } else {
                return response()->json(['code' => 1, 'message' => 'Data Jabatan Berhasil diupdate.']);
            }
        }
    }

    public function destroy(Request $request)
    {
        $position_id = $request->position_id;

        $position = Position::find($position_id)->delete();

        if ($position) {
            return response()->json(['code' => 1, 'message' => 'Data Jabatan Berhasil dihapus.']);
        } else {
            return response()->json(['code' => 0, 'message' => 'Maaf sedang terjadi masalah.']);
        }
    }
}
