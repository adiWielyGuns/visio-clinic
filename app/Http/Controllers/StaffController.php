<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    public function index()
    {
        return view('staff/staff');
    }

    public function datatable(Request $req)
    {
        $data = User::where('role_id', '!=', 3)->get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('staff/action', compact('data'));
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function generatekode(Request $req)
    {
        $dept = Role::find($req->param);
        $kode =  $dept->name[0];
        $sub = strlen($kode) + 1;
        $index = User::selectRaw('max(substring(user_id,' . $sub . ')) as id')
            ->where('user_id', 'like', $kode . '%')
            ->first();

        $collect = User::selectRaw('substring(user_id,' . $sub . ') as id')
            ->get();

        $count = (int)$index->id;
        $collect_id = [];
        for ($i = 0; $i < count($collect); $i++) {
            array_push($collect_id, (int)$collect[$i]->id);
        }

        $flag = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($flag == 0) {
                if (!in_array($i + 1, $collect_id)) {
                    $index = $i + 1;
                    $flag = 1;
                }
            }
        }

        if ($flag == 0) {
            $index = (int)$index->id + 1;
        }


        $index = str_pad($index, 4, '0', STR_PAD_LEFT);

        $kode = $kode . $index;

        return Response()->json(['status' => 1, 'kode' => $kode]);
    }

    public function create(Request $req)
    {
        return view('staff/create_staff');
    }

    public function edit(Request $req)
    {
        $data = User::findOrFail($req->id);
        return view('staff/edit_staff', compact('data'));
    }

    public function show(Request $req)
    {
        $data = User::findOrFail($req->id);
        return view('staff/show_staff', compact('data'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();
            $validator = Validator::make(
                $input,
                [
                    'email'       => 'required|unique:users|email',
                    'username'       => 'unique:users',
                ],
                [
                    'email.email'        => 'Format Email Salah',
                    'email.unique'        => 'Email sudah ada',
                    'username.unique'        => 'Username sudah ada',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), Response::HTTP_BAD_REQUEST);
            }

            $input['created_by'] = me();
            $input['updated_by'] = me();
            $input['username'] = $req->user_id;
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);
            $input['password_change_date'] = now();
            $input['password'] = Hash::make(str_replace('/', '', $req->tanggal_lahir));

            User::create($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function update(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();
            $validator = Validator::make(
                $input,
                [
                    'email'       => 'required|unique:users' . ($req->id == 'undefined' ? '' : ",email,$req->id"),
                ],
                [
                    'email.email'        => 'Format Email Salah',
                    'email.unique'        => 'Email sudah ada',
                ]
            );

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), Response::HTTP_BAD_REQUEST);
            }

            $input['updated_by'] = me();
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);

            User::find($req->id)->update($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function delete(Request $req)
    {
        User::findOrFail($req->id)->delete();
        return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
    }
}
