<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomTypeCollection;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RoomTypeCollection
     */
    public function index()
    {
        $rooms = RoomType::all();
        return new RoomTypeCollection($rooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response|RoomTypeResource
     */
    public function store(Request $request)
    {
        if (auth()->user()->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action."
                ],
                403
            );
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'name'       => 'required|max:50',
            'beds'       => 'required|integer|min:1|max:10',
            'spaces'     => 'required|integer|min:1|max:5',
            'max_people' => 'required|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response(
                [
                    'error' => $validator->errors(),
                    'Validation Error',
                ]
            );
        }

        $roomType = RoomType::create($data);

        return new RoomTypeResource($roomType);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\RoomType $roomType
     *
     * @return RoomTypeResource
     */
    public function show(RoomType $roomType)
    {
        return new RoomTypeResource($roomType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\RoomType $roomType
     *
     * @return \Illuminate\Http\Response|RoomTypeResource
     */
    public function update(Request $request, RoomType $roomType)
    {
        if (auth()->user()->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action."
                ],
                403
            );
        }
        $roomType->update($request->all());

        return new RoomTypeResource($roomType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\RoomType $roomType
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoomType $roomType)
    {
        if (auth()->user()->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action."
                ],
                403
            );
        }

        if ($roomType->id < 4) {
            return response(['error' => 'Room type can\'t be deleted.'], 422);
        }
        $roomType->delete();

        return response(['message' => 'Room type deleted']);
    }

}
