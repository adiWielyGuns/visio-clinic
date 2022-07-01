<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(Request $req)
    {
        return view('setting');
    }

    public function create(Request $req)
    {
        $data = Auth::user();
        return view('preferensi', compact('data'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {
            $req->id = Auth::user()->id;
            $input = $req->all();
            $validator = Validator::make(
                $input,
                [
                    'email'       => 'required|unique:users' . (Auth::user()->id == 'undefined' ? '' : ",email,$req->id"),
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
            $input['password'] = Hash::make($req->password);
            $input['password_change_date'] = now();

            User::find($req->id)->update($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil dirubah']);
        });
    }
}
