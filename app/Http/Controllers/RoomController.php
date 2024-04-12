<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomBookingsResource;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return RoomCollection
     */
    public function index()
    {
        $rooms = Room::all();
        return new RoomCollection($rooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response|RoomResource
     */
    public function store(Request $request)
    {
        if (auth()->user()->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }
        $data = $request->all();

        $validator = Validator::make($data, [
            'name'    => 'required|max:50',
            'type_id' => 'required|integer|min:1',
            'price'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response(
                [
                    'error' => $validator->errors(),
                    'Validation Error',
                ]
            );
        }

        $room = Room::create($data);

        return new RoomResource($room);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Room $room
     *
     * @return RoomResource
     */
    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Room $room
     *
     * @return \Illuminate\Http\Response|RoomResource
     */
    public function update(Request $request, Room $room)
    {
        if (auth()->user()->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }
        $room->update($request->all());

        return new RoomResource($room);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Room $room
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        if (auth()->user()->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }

        if ($room->id < 46) {
            return response(['error' => 'Room can\'t be deleted.'], 422);
        }

        $room->delete();

        return response(['message' => 'Room deleted'], 200);
    }

    public function bookings(Room $room)
    {
        if (auth()->user()->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }

        return new RoomBookingsResource($room);
    }
}
