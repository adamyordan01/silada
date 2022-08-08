<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDocumentTypeRequest;
use Carbon\Carbon;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
    public function index()
    {
        return view('document-type.index');
    }

    public function getDocumentType(Request $request)
    {
        $documentTypes = DocumentType::select(['id', 'name']);

        return DataTables::of($documentTypes)
            ->addIndexColumn()
            ->addColumn('action', function ($documentType) {
                $role = Auth::user()->role->name;

                if ($role == 'admin') {
                    $buttonEdit = '<button data-toggle="tooltip" data-placement="top" title="Edit Jenis Dokumen" data-id="'.$documentType->id.'" id="editButton" class="btn btn-info mr-2"><i class="fas fa-edit"></i></button>';
                    
                } else {
                    $buttonEdit = '';
                }
                // $buttonDelete = '<a href="/document-type/delete/'.$documentType->id.'" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
                // return $buttonEdit.' '.$buttonDelete;
                return $buttonEdit;
            })
            ->editColumn('created_at', function ($documentType) {
                return Carbon::parse($documentType->created_at)->format('d-m-Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:document_types',
        ], [
            'name.required' => 'Jenis Dokumen tidak boleh kosong',
            'name.max' => 'Jenis Dokumen tidak boleh lebih dari 255 karakter',
            'name.unique' => 'Jenis Dokumen sudah ada',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray(),
            ]);
        } else {
            $documentType = new DocumentType;
            $documentType->name = $request->name;
            $documentType->save();
            return response()->json([
                'code' => 1,
                'message' => 'Jenis Dokumen berhasil ditambahkan',
            ]);
        }
    }

    public function getDocumentDetail(Request $request)
    {
        $documentType_id = $request->document_type_id;
        $documentType = DocumentType::find($documentType_id);
        return response()->json([
            'detail' => $documentType
        ]);
    }

    public function update(UpdateDocumentTypeRequest $request)
    {
        $document_type_id = $request->document_type_id;

        $document_type = DocumentType::find($document_type_id);
        $document_type->name = $request->name;
        $query = $document_type->save();

        if ($query) {
            return response()->json([
                'code' => 1,
                'message' => 'Jenis Dokumen berhasil di update',
            ]);
        } else {
            return response()->json([
                'code' => 0,
                'error' => 'Jenis Dokumen gagal di update',
            ]);
        }
    }
}
