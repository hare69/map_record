<?php

namespace App\Http\Controllers;

use App\Models\NightView;
use Illuminate\Http\Request;

class NightViewController extends Controller
{
    public function index()
    {
        return view('night_view.index');
    }

    public function list(Request $request)
    {
        // TODO: バリデーションは省略してます

        $latitude = $request->latitude;   // 緯度
        $longitude = $request->longitude; // 経度
        $night_views = [];

        try {

            $night_views = NightView::selectDistance($longitude, $latitude)
                ->orderBy('distance', 'asc')
                ->take(10)
                ->get();

        } catch(\Exception $e) {}

        return [
            'result' => true,
            'night_views' => $night_views
        ];
    }
}
