<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return [
        'response' => 'error',
        'remark'   => 'access denined',
    ];
});

Route::get('confirm/{code}', function ($code) {
    App\CONFIRMPOOL::where('ticket_timeout', '<', Carbon\Carbon::now())->delete();

    if (!(App\CONFIRMPOOL::where('ticket_code', $code)->exists())) {
        return view('page.confirm.notfound');
    }

    $data = App\CONFIRMPOOL::where('ticket_code', $code)->first();

    $check = App\FLIGHT::select('user_vid')->where('aircraft_callsign', $data['aircraft_callsign'])->first();
    if ($check['user_vid'] == 'null') {
        App\CONFIRMPOOL::where('ticket_code', $code)->delete();

        return view('page.confirm.tooslow');
    }

    $flight = new App\FLIGHT();
    $flight->event_id = $data['event_id'];
    $flight->flight_id = $data['flight_id'];
    $flight->user_division = $data['user_division'];
    $flight->user_vid = $data['user_vid'];
    $flight->user_email = $data['user_email'];
    $flight->user_rating = $data['user_rating'];
    $flight->aircraft_callsign = $data['aircraft_callsign'];
    $flight->aircraft_model = $data['aircraft_model'];
    $flight->flight_rule = $data['flight_rule'];
    $flight->flight_type = $data['flight_type'];
    $flight->flight_load = $data['flight_load'];
    $flight->time_departure = $data['time_departure'];
    $flight->time_arrival = $data['time_arrival'];
    $flight->save();

    if (App\FLIGHT::where('flight_id', $data['flight_id'])->exists()) {
        App\CONFIRMPOOL::where('ticket_code', $code)->delete();

        return view('page.confirm.success');
    } else {
        return view('page.confirm.crash');
    }
});

Route::fallback(function () {
    return [
        'response' => 'error',
        'remark'   => 'access denined',
    ];
});
