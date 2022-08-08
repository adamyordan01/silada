<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\DocumentType;
use App\Models\Position;
use App\Models\Shared;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ArchiveController extends Controller
{
    public function index()
    {
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $role = Auth::user()->role->name;

        if ($role == 'admin') {
            $documentTypes = DocumentType::orderBy('name', 'asc')->get();
        } elseif ($role == 'user') {
            $documentTypes = DocumentType::whereNotIn('id', [3,4,5,6] )->orderBy('name', 'asc')->get();
        } elseif ($role == 'ppats') {
            $documentTypes = DocumentType::whereIn('id', [3,4,5,6] )->orderBy('name', 'asc')->get();
        }

        $positions = Position::whereNotIn('id', [1, 10])->get();

        return view('archives.index', [
            'documentTypes' => $documentTypes,
            'months' => $months,
            'positions' => $positions,
        ]);
    }

    public function getArchives()
    {
        $role = Auth::user()->role->name;

        if ($role == 'admin') {
            $archives = DB::select("
                SELECT
                    users.`name` AS user_name,
                    positions.`name` AS position,
                    archives.*,
                    document_types.`name` AS document_type
                FROM
                    archives
                    INNER JOIN positions ON archives.position_id = positions.id
                    INNER JOIN users ON archives.user_id = users.id
                    INNER JOIN document_types ON archives.document_type_id = document_types.id
            ");
        } else {
            $archives = DB::select("
                SELECT
                    users.`name` AS user_name,
                    positions.`name` AS position,
                    archives.*,
                    document_types.`name` AS document_type,
                    shareds.position_id,
                    shareds.archive_id 
                FROM
                    archives
                    INNER JOIN positions ON archives.position_id = positions.id
                    INNER JOIN users ON archives.user_id = users.id
                    INNER JOIN document_types ON archives.document_type_id = document_types.id
                    LEFT JOIN shareds ON archives.id = shareds.archive_id 
                WHERE
                    archives.position_id = ?
                    OR shareds.position_id = ?
                ORDER BY
                    archives.archive_date DESC
                " , [Auth::user()->position_id, Auth::user()->position_id]);
        }

        return DataTables::of($archives)
            ->addIndexColumn()
            ->addColumn('action', function ($archive) {
                // $buttonShow = '<button data-toggle="tooltip" data-placement="top" title="Lihat Dokumen" data-id="'.$archive->file.'" id="showButton" class="btn btn-info"><i class="fas fa-eye"></i></button>';
                // $buttonShow = '<a href="'{{ asset('files/', $archive->file) }}'" class="btn btn-info"><i class="fas fa-eye"></i></a>';
                // download file from server

                // bener
                // $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';

                // $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" onclick="share('. $archive->id .')" class="btn btn-add"><i class="fas fa-share"></i></button>';
                // check is user owner of this document if yes show share button else show download button
                $role = Auth::user()->role->name;
                if ($role == 'admin') {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                }
                
                if (Auth::user()->id == $archive->user_id) {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                } else {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    return $buttonDownload;
                    
                }

                // return $buttonDownload . ' ' . $buttonShare;
                
                
            })
            ->editColumn('archive_date', function ($archive) {
                return Carbon::parse($archive->archive_date)->format('d-m-Y');
            })
            ->editColumn('created_at', function ($archive) {
                return Carbon::parse($archive->created_at)->format('d-m-Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getIncomingLetter()
    {
        $archives = DB::select("
            SELECT
                users.`name` AS user_name,
                archives.*,
                document_types.`name` AS document_type,
                shareds.position_id,
                shareds.archive_id 
            FROM
                archives
                INNER JOIN positions ON archives.position_id = positions.id
                INNER JOIN users ON archives.user_id = users.id
                INNER JOIN document_types ON archives.document_type_id = document_types.id
                LEFT JOIN shareds ON archives.id = shareds.archive_id 
            WHERE
                archives.document_type_id = 1
                AND archives.position_id = ?
                OR shareds.position_id = ?
            ORDER BY
                archives.archive_date DESC
            " , [Auth::user()->position_id, Auth::user()->position_id]);

        return DataTables::of($archives)
            ->addIndexColumn()
            ->addColumn('action', function ($archive) {
                $role = Auth::user()->role->name;
                if ($role == 'admin') {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                }
                
                if (Auth::user()->id == $archive->user_id) {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                } else {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    return $buttonDownload;
                    
                }

                // return $buttonDownload . ' ' . $buttonShare;
                
                
            })
            ->editColumn('archive_date', function ($archive) {
                return Carbon::parse($archive->archive_date)->format('d-m-Y');
            })
            ->editColumn('created_at', function ($archive) {
                return Carbon::parse($archive->created_at)->format('d-m-Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function incomingLetter()
    {
        return view('archives.incoming_letter');
    }

    public function getOutgoingLetter()
    {
        $archives = DB::select("
            SELECT
                users.`name` AS user_name,
                archives.*,
                document_types.`name` AS document_type,
                shareds.position_id,
                shareds.archive_id 
            FROM
                archives
                INNER JOIN positions ON archives.position_id = positions.id
                INNER JOIN users ON archives.user_id = users.id
                INNER JOIN document_types ON archives.document_type_id = document_types.id
                LEFT JOIN shareds ON archives.id = shareds.archive_id 
            WHERE
                archives.document_type_id = 2
                AND archives.position_id = ?
                OR shareds.position_id = ?
            ORDER BY
                archives.archive_date DESC
            " , [Auth::user()->position_id, Auth::user()->position_id]);

        return DataTables::of($archives)
            ->addIndexColumn()
            ->addColumn('action', function ($archive) {
                $role = Auth::user()->role->name;
                if ($role == 'admin') {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                }
                
                if (Auth::user()->id == $archive->user_id) {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                } else {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    return $buttonDownload;
                    
                }

                // return $buttonDownload . ' ' . $buttonShare;
                
                
            })
            ->editColumn('archive_date', function ($archive) {
                return Carbon::parse($archive->archive_date)->format('d-m-Y');
            })
            ->editColumn('created_at', function ($archive) {
                return Carbon::parse($archive->created_at)->format('d-m-Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function outgoingLetter()
    {
        return view('archives.outgoing_letter');
    }

    public function getAjb()
    {
        $archives = DB::select("
            SELECT
                users.`name` AS user_name,
                archives.*,
                document_types.`name` AS document_type,
                shareds.position_id,
                shareds.archive_id 
            FROM
                archives
                INNER JOIN positions ON archives.position_id = positions.id
                INNER JOIN users ON archives.user_id = users.id
                INNER JOIN document_types ON archives.document_type_id = document_types.id
                LEFT JOIN shareds ON archives.id = shareds.archive_id 
            WHERE
                archives.document_type_id = 3
                AND archives.position_id = ?
                OR shareds.position_id = ?
            ORDER BY
                archives.archive_date DESC
            " , [Auth::user()->position_id, Auth::user()->position_id]);

        return DataTables::of($archives)
        ->addIndexColumn()
        ->addColumn('action', function ($archive) {
            $role = Auth::user()->role->name;
            if ($role == 'admin') {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                return $buttonDownload . ' ' . $buttonShare;
            }
            
            if (Auth::user()->id == $archive->user_id) {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                return $buttonDownload . ' ' . $buttonShare;
            } else {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                return $buttonDownload;
                
            }

            // return $buttonDownload . ' ' . $buttonShare;
            
            
        })
        ->editColumn('archive_date', function ($archive) {
            return Carbon::parse($archive->archive_date)->format('d-m-Y');
        })
        ->editColumn('created_at', function ($archive) {
            return Carbon::parse($archive->created_at)->format('d-m-Y');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function aktaHibah()
    {
        return view('archives.akta_hibah');
    }

    public function getAktaHibah()
    {
        $archives = DB::select("
            SELECT
                users.`name` AS user_name,
                archives.*,
                document_types.`name` AS document_type,
                shareds.position_id,
                shareds.archive_id 
            FROM
                archives
                INNER JOIN positions ON archives.position_id = positions.id
                INNER JOIN users ON archives.user_id = users.id
                INNER JOIN document_types ON archives.document_type_id = document_types.id
                LEFT JOIN shareds ON archives.id = shareds.archive_id 
            WHERE
                archives.document_type_id = 5
                AND archives.position_id = ?
                OR shareds.position_id = ?
            ORDER BY
                archives.archive_date DESC
            " , [Auth::user()->position_id, Auth::user()->position_id]);

        return DataTables::of($archives)
        ->addIndexColumn()
        ->addColumn('action', function ($archive) {
            $role = Auth::user()->role->name;
            if ($role == 'admin') {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                return $buttonDownload . ' ' . $buttonShare;
            }
            
            if (Auth::user()->id == $archive->user_id) {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                return $buttonDownload . ' ' . $buttonShare;
            } else {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                return $buttonDownload;
                
            }

            // return $buttonDownload . ' ' . $buttonShare;
            
            
        })
        ->editColumn('archive_date', function ($archive) {
            return Carbon::parse($archive->archive_date)->format('d-m-Y');
        })
        ->editColumn('created_at', function ($archive) {
            return Carbon::parse($archive->created_at)->format('d-m-Y');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function ajb()
    {
        return view('archives.ajb');
    }

    public function getAphb()
    {
        $archives = DB::select("
            SELECT
                users.`name` AS user_name,
                archives.*,
                document_types.`name` AS document_type,
                shareds.position_id,
                shareds.archive_id 
            FROM
                archives
                INNER JOIN positions ON archives.position_id = positions.id
                INNER JOIN users ON archives.user_id = users.id
                INNER JOIN document_types ON archives.document_type_id = document_types.id
                LEFT JOIN shareds ON archives.id = shareds.archive_id 
            WHERE
                archives.document_type_id = 4
                AND archives.position_id = ?
                OR shareds.position_id = ?
            ORDER BY
                archives.archive_date DESC
            " , [Auth::user()->position_id, Auth::user()->position_id]);

        return DataTables::of($archives)
        ->addIndexColumn()
        ->addColumn('action', function ($archive) {
            $role = Auth::user()->role->name;
            if ($role == 'admin') {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                return $buttonDownload . ' ' . $buttonShare;
            }
            
            if (Auth::user()->id == $archive->user_id) {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                return $buttonDownload . ' ' . $buttonShare;
            } else {
                $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                return $buttonDownload;
                
            }

            // return $buttonDownload . ' ' . $buttonShare;
            
            
        })
        ->editColumn('archive_date', function ($archive) {
            return Carbon::parse($archive->archive_date)->format('d-m-Y');
        })
        ->editColumn('created_at', function ($archive) {
            return Carbon::parse($archive->created_at)->format('d-m-Y');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function aphb()
    {
        return view('archives.aphb');
    }

    public function getAphgb()
    {
        $archives = DB::select("
        SELECT
            users.`name` AS user_name,
            archives.*,
            document_types.`name` AS document_type,
            shareds.position_id,
            shareds.archive_id 
        FROM
            archives
            INNER JOIN positions ON archives.position_id = positions.id
            INNER JOIN users ON archives.user_id = users.id
            INNER JOIN document_types ON archives.document_type_id = document_types.id
            LEFT JOIN shareds ON archives.id = shareds.archive_id 
        WHERE
            archives.document_type_id = 6
            AND archives.position_id = ?
            OR shareds.position_id = ?
        ORDER BY
            archives.archive_date DESC
        " , [Auth::user()->position_id, Auth::user()->position_id]);

        return DataTables::of($archives)
            ->addIndexColumn()
            ->addColumn('action', function ($archive) {
                $role = Auth::user()->role->name;
                if ($role == 'admin') {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                }
                
                if (Auth::user()->id == $archive->user_id) {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    $buttonShare = '<button data-toggle="tooltip" data-placement="top" title="Bagikan Dokumen" data-id="' . $archive->id . '" id="shareButton" class="btn btn-add"><i class="fas fa-share"></i></button>';

                    return $buttonDownload . ' ' . $buttonShare;
                } else {
                    $buttonDownload = '<a href="' . asset($archive->file) . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Lihat Dokumen"><i class="fas fa-download"></i></a>';
                    return $buttonDownload;
                    
                }

                // return $buttonDownload . ' ' . $buttonShare;
                
                
            })
            ->editColumn('archive_date', function ($archive) {
                return Carbon::parse($archive->archive_date)->format('d-m-Y');
            })
            ->editColumn('created_at', function ($archive) {
                return Carbon::parse($archive->created_at)->format('d-m-Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function aphgb()
    {
        return view('archives.aphgb');
    }

    public function getIncomingLetterCamat(Request $request)
    {
        
    }

    public function create()
    {
        $positions = Position::all();

        return view('archives.share', [
            'positions' => $positions,
        ]);
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role->name;
        
        if ($role == 'admin') {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'archive_date' => 'required',
                'archive_number' => 'required',
                'title' => 'required',
                'origin' => 'required',
                'sender' => 'required',
                'position' => 'required',
                'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
                ], [
                'type.required' => 'Jenis Dokumen harus diisi',
                'archive_date.required' => 'Tanggal Dokumen harus diisi',
                'archive_number.required' => 'Nomor Arsip harus diisi',
                'title.required' => 'Nama dokumen harus diisi',
                'origin.required' => 'Asal Dokumen harus diisi',
                'sender.required' => 'Pengirim Dokumen harus diisi',
                'position.required' => 'Pemilik Berkas harus dipilih',
                'file.required' => 'File harus diisi',
                'file.mimes' => 'File harus berupa file PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX',
                'file.max' => 'File tidak boleh lebih dari 5 MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 0,
                    'error' => $validator->errors()->toArray(),
                ]);
            } else {
                $file = $request->file('file');
                $fileName = time() . "-" .  $file->getClientOriginalName();
                $file->move(public_path('files'), $fileName);
                $filePath = 'files/' . $fileName;
    
                $archive_date = Carbon::parse($request->archive_date)->format('Y-m-d');
                
                $archive = Archive::create([
                    'document_type_id' => $request->type,
                    'archive_date' => $archive_date,
                    'archive_number' => $request->archive_number,
                    'title' => $request->title,
                    'origin' => $request->origin,
                    'sender' => $request->sender,
                    'position_id' => $request->position,
                    'file' => $filePath,
                    'user_id' => Auth::user()->id,
                ]);
                if ($archive) {
                    return response()->json([
                        'code' => 1,
                        'message' => 'Arsip berhasil ditambahkan',
                    ]);
                } else {
                    return response()->json([
                        'code' => 0,
                        'message' => 'Arsip gagal ditambahkan',
                    ]);
                }
            }
        } else {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'archive_date' => 'required',
                'archive_number' => 'required',
                'title' => 'required',
                'origin' => 'required',
                'sender' => 'required',
                'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
                ], [
                'type.required' => 'Jenis Dokumen harus diisi',
                'archive_date.required' => 'Tanggal Dokumen harus diisi',
                'archive_number.required' => 'Nomor Arsip harus diisi',
                'title.required' => 'Nama dokumen harus diisi',
                'origin.required' => 'Asal Dokumen harus diisi',
                'sender.required' => 'Pengirim Dokumen harus diisi',
                'file.required' => 'File harus diisi',
                'file.mimes' => 'File harus berupa file PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX',
                'file.max' => 'File tidak boleh lebih dari 5 MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 0,
                    'error' => $validator->errors()->toArray(),
                ]);
            } else {
                $file = $request->file('file');
                $fileName = time() . "-" .  $file->getClientOriginalName();
                $file->move(public_path('files'), $fileName);
                $filePath = 'files/' . $fileName;
    
                $archive_date = Carbon::parse($request->archive_date)->format('Y-m-d');
                
                $archive = Archive::create([
                    'document_type_id' => $request->type,
                    'archive_date' => $archive_date,
                    'archive_number' => $request->archive_number,
                    'title' => $request->title,
                    'origin' => $request->origin,
                    'sender' => $request->sender,
                    'position_id' => auth()->user()->position_id,
                    'file' => $filePath,
                    'user_id' => Auth::user()->id,
                ]);
                if ($archive) {
                    return response()->json([
                        'code' => 1,
                        'message' => 'Arsip berhasil ditambahkan',
                    ]);
                } else {
                    return response()->json([
                        'code' => 0,
                        'message' => 'Arsip gagal ditambahkan',
                    ]);
                }
            }
        }
    }

    public function share(Request $request)
    {
        // dd($request->all());
        $archive_id = $request->archive_id;
        $position = $request->position;

        foreach ($position as $key => $value) {
            $share = Shared::updateOrCreate([
                'archive_id' => $archive_id,
                'position_id' => $value,
            ]);
        }

        if ($share) {
            return response()->json([
                'code' => 1,
                'message' => 'Arsip berhasil dibagikan',
            ]);
        } else {
            return response()->json([
                'code' => 0,
                'message' => 'Arsip gagal dibagikan',
            ]);
        }
    }
}
