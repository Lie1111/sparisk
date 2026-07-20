<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sticker;
use App\Models\Scan;

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        $tag = $request->tag;
        $lbl = $request->lbl;
        $enc = $request->enc;
        $device = json_encode($request->device);

        $sticker = Sticker::where('tag', $tag)->where('label', $lbl)->first();

        if (!$sticker) {
            return response()->json([
                'message' => 'Sticker not found',
                'data' => null
            ], 404);
        }

        Scan::create([
            'sticker_id' => $sticker->id,
            'device' => json_encode($device) ?? null
        ]);

        return response()->json([
            'message' => 'Scan fetched successfully',
            'data' => []
        ], 200);
    }
}