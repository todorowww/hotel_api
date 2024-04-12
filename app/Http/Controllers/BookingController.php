<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingCollection;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BookingCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->id !== 1) {
            $bookings = Booking::where(['user_id' => auth()->user()->id])->orderBy('start_date', 'ASC')->get();
        } else {
            $bookings = Booking::orderBy('start_date', 'ASC')->get();
        }

        return (new BookingCollection($bookings))->isLimited(false)->expandResources(false);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id'        => 'required|numeric|exists:users,id',
            'room_id'        => 'required|numeric|exists:rooms,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'people'         => 'required|numeric|min:1',
            'with_breakfast' => 'boolean',
            'with_lunch'     => 'boolean',
            'with_dinner'    => 'boolean',
            'with_minibar'   => 'boolean',
            'with_laundry'   => 'boolean',
        ]);

        if ($validator->fails()) {
            return response(
                [
                    'error' => $validator->errors(),
                    'Validation Error',
                ],
                422
            );
        }

        $bookings = Booking::where(function (Builder $q) use ($data) {
            $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
        })
            ->where('user_id', '=', $data['user_id'])
            ->where('room_id', '=', $data['room_id']);

        if ($bookings->count() > 0) {
            return response(['error' => "You already booked the selected room on selected dates."], 422);
        }

        $room = Room::where('id', '=', $data['room_id'])->first();
        $roomType = $room->type;

        $startDate = Carbon::make($data['start_date']);
        $endDate = Carbon::make($data['end_date']);
        $duration = $endDate->diff($startDate)->days;

        $data['total_price'] = $duration * $room->price;
        $people = $data['people'];

        if ($people > $roomType->max_people) {
            return response(
                [
                    'error' => "You can't book a \"{$roomType->name}\" for {$people} persons. {$roomType->name} can support only {$roomType->max_people} persons.",
                ],
                422
            );
        }

        $booking = Booking::create($data);

        return response(
            [
                'booking' => new BookingResource($booking),
                'message' => 'Success',
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Booking $booking
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        if (auth()->user()->id !== 1 && $booking->user->id !== auth()->user()->id) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }
        return response(
            new BookingResource($booking),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booking $booking
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Booking $booking
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        if (auth()->user()->id !== 1 && $booking->user->id !== auth()->user()->id) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }

        $booking->delete();

        return response(['message' => 'Booking deleted']);
    }
}
