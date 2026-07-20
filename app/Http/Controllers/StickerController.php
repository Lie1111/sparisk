<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sticker;
use App\Models\User;

class StickerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->q;
        if ($request->q === 'inactive') {
            $status = '0';
        } else if ($request->q === 'product') {
            $status = '1';
        }

        $query = Sticker::query();

        if ($status !== '')
            $query->where([['status', '=', $status]]);
        if ($request->q !== 'inactive' && $request->q !== 'product' && $request->q !== 'box')
            $query->orWhere('label', 'LIKE', "%{$request->q}%")->orWhere('tag', 'LIKE', "%{$request->q}%");

        $stickers = $query->paginate(
            $perPage = 25,
            $columns = ['*'],
            $pageName = 'stickers'
        );

        $stickers->setPath('');

        $users = User::all();

        return Inertia::render('smartsecure/index', [
            "stickers" => $stickers,
            "users" => $users,
            "query" => $request->q ?? $request->q,
        ]);
    }


    public function sticker_create(Request $request)
    {
        $stickers = $request["data"];

        if ($stickers && count($stickers) > 0) {
            $dataToInsert = [];
            foreach ($stickers as $sticker) {
                $dataToInsert[] = [
                    'tag' => $sticker[1],
                    'label' => substr($sticker[2], 0, 28),
                    'enc' => substr($sticker[2], 28),
                    'status' => "0",
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Sticker::insert($dataToInsert);
        }

        return redirect()->route('smartsecure.index', ['stickers' => $request->page]);
    }

    public function sticker_update(Request $request)
    {
        $sticker = Sticker::findOrFail($request->id);
        $sticker->tag = $request->tag;
        $sticker->label = $request->label;
        $sticker->enc = $request->enc;
        $sticker->status = $request->status;
        $sticker->save();

        return redirect()->route('smartsecure.index', ['stickers' => $request->page]);
    }

    public function sticker_destroy(Request $request)
    {
        $sticker = Sticker::findOrFail($request->id);
        $sticker->delete();

        return redirect()->route('smartsecure.index', ['stickers' => $request->page]);
    }
}
