<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    //
    public function all_item (Request $request) {
        $items = Item::all();
        return response()->json([
            "status"=> true,
            "message"=> "Items",
            "data"=> $items
        ]);
    }

    public function create_item (Request $request) {
        $validate = Validator::make($request->all(), [
            "task"=> "required"
        ]);

        if($validate->fails()) {
            return response()->json([
                "status"=> false,
                "message"=> "Validation Error"
            ], 401);
        }

        $item = Item::create([
            "task"=> $request->task
        ]);

        return response()->json([
            "status"=> true,
            "message"=> "Item Created",
            "data"=> $item
        ], 200);
    }

    public function update_item (Request $request, $id) {
        $validate = Validator::make($request->all(), [
            "task"=> "required"
        ]);

        if($validate->fails()) {
            return response()->json([
                "status"=> false,
                "message"=> "Validation Error"
            ], 401);
        }

        $item = Item::find($id);
        $item->update([
            "task"=> $request->task
        ]);

        return response()->json([
            "status"=> true,
            "message"=> "Item Updated",
            "data"=> $item
        ], 200);
    }

    public function delete_item (Request $request, $id) {
        $item = Item::find($id);

        if(!$item) {
            return response()->json([
                "status"=> false,
                "message"=> "No Item"
            ], 401);
        }
        
        $item->delete();

        return response()->json([
            "status"=> true,
            "message"=> "Item Deleted"
        ]);
    }

}
