<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    public function index()
    {
        return view('item/item');
    }

    public function datatable(Request $req)
    {
        $data = Item::all();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('item/action', compact('data'));
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function generatekode(Request $req)
    {
        $kode =  'RM';
        $sub = strlen($kode) + 1;
        $index = Item::selectRaw('max(substring(id_item,' . $sub . ')) as id')
            ->where('id_item', 'like', $kode . '%')
            ->first();

        $collect = Item::selectRaw('substring(id_item,' . $sub . ') as id')
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
        $kode = $this->generatekode($req)->getData()->kode;
        return view('item/create_item', compact('kode'));
    }

    public function edit(Request $req)
    {
        $data = Item::findOrFail($req->id);
        return view('item/edit_item', compact('data'));
    }

    public function show(Request $req)
    {
        $data = Item::findOrFail($req->id);

        return view('item/show_item', compact('data'));
    }

    public function store(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();


            $input['id'] = Item::max('id') + 1;
            $input['created_by'] = me();
            $input['updated_by'] = me();
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);

            Item::create($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
        });
    }

    public function update(Request $req)
    {
        return DB::transaction(function () use ($req) {

            $input = $req->all();

            $input['updated_by'] = me();
            $input['tanggal_lahir'] = dateStore($req->tanggal_lahir);

            Item::find($req->id)->update($input);
            return Response()->json(['status' => 1, 'message' => 'Data berhasil diupdate']);
        });
    }

    public function delete(Request $req)
    {
        Item::findOrFail($req->id)->delete();
        return Response()->json(['status' => 1, 'message' => 'Data berhasil disimpan']);
    }
}
