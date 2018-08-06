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

    $check = App\FLIGHT::select('user_vid')->where('event_id', $data['event_id'])->where('flight_id', $data['flight_id'])->first();
    if ($check['user_vid'] == 'null') {
        App\CONFIRMPOOL::where('ticket_code', $code)->delete();

        return view('page.confirm.tooslow');
    }

    $flight = [
        'user_division'  => $data['user_division'],
        'user_vid'       => $data['user_vid'],
        'user_email'     => $data['user_email'],
        'user_rating'    => $data['user_rating'],
        'aircraft_model' => $data['aircraft_model'],
        'flight_rule'    => $data['flight_rule'],
        'flight_type'    => $data['flight_type'],
        'flight_load'    => $data['flight_load'],
        'time_departure' => $data['time_departure'],
        'time_arrival'   => $data['time_arrival'],
    ];

    if (App\FLIGHT::where('flight_id', $data['flight_id'])->update($flight)) {
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
