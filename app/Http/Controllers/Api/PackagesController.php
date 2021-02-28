<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Package;
use App\Model\Game;
use App\Model\PackagesAsignToGame;


class PackagesController extends Controller
{
    /**
     * get Sport Packages with assigned games.
     *
     * @param Request $request
     *
     * @return json
     */

    public function getSportPackages(Request $request)
    {
        $packageDetail = Package::selectRaw('id,packageName')
                                ->where('isActive', 1)
                                ->get();
   
        if (!$packageDetail->isEmpty()) {
            $listpackageData = array();
            foreach ($packageDetail as $key => $result) {
                $nestedData['id']           = $result['id'];
                $nestedData['packageName']  = $result['packageName'];
                $nestedData['isSelected']   = false ;
                $nestedData['gamesData']    = self::get_games($result['id']);
                $listpackageData[]          = $nestedData;
            }

            return $this->toJson($listpackageData, trans('api.package.detail'), 1);

        } else {
            return $this->toJson(null, trans('api.package.error'), 0);
        }
    }

    public function get_games($packId)
    {
        $gameIds = PackagesAsignToGame::selectRaw('*')->where('packageId',$packId)->distinct('gameId')->pluck('gameId')->toArray();
        return Game::selectRaw('games.id,games.gameName,games.gameFullName')->where('isActive',1)->whereIn('games.id', $gameIds)->get();
    }
}
