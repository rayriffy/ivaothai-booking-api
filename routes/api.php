<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('addaccess', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['master_key']) || empty($request['data']['division'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if ($request['master_key'] != env('MASTER_KEY', 'riffydaddyallhome')) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];
    $access_key = str_random(32);

    $access = new App\ACCESS();
    $access->key = $access_key;
    $access->division = $data['division'];
    $access->isallowed = 1;
    $access->save();

    return [
      'response' => 'success',
  ];
});

Route::post('getaccess', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['access'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = App\ACCESS::select('division', 'is_allowed')->where('key', $request['access_key'])->first();

    return [
      'response' => 'success',
      'data'     => $data,
  ];
});

Route::post('getevents', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    if (!(App\EVENT::where('token', $request['access_key'])->exists())) {
        return [
        'response' => 'success',
        'data'     => null,
    ];
    }
    $data = App\EVENT::select('event_id', 'event_name', 'booktime_start', 'booktime_end', 'airport_departure', 'airport_arrival')->where('token', $request['access_key'])->get();

    foreach ($data as $dat) {
        $event[] = [
          'event' => [
              'id'   => $dat['event_id'],
              'name' => $dat['event_name'],
          ],
          'booking_time' => [
              'start' => $dat['booktime_start'],
              'end'   => $dat['booktime_end'],
          ],
          'airport' => [
              'departure' => $dat['airport_departure'],
              'arrival'   => $dat['airport_arrival'],
          ],
      ];
    }

    return [
      'response' => 'success',
      'data'     => $event,
  ];
});

Route::post('getevent', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['data']['event']['id'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];

    if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
        return [
        'response' => 'error',
        'remark'   => 'event not found',
    ];
    }

    $dat = App\EVENT::select('event_id', 'event_name', 'booktime_start', 'booktime_end', 'airport_departure', 'airport_arrival')->where('token', $request['access_key'])->where('event_id', $data['event']['id'])->first();

    $event = [
      'event' => [
          'id'   => $dat['event_id'],
          'name' => $dat['event_name'],
      ],
      'booking_time' => [
          'start' => $dat['booktime_start'],
          'end'   => $dat['booktime_end'],
      ],
      'airport' => [
          'departure' => $dat['airport_departure'],
          'arrival'   => $dat['airport_arrival'],
      ],
  ];

    return [
      'response' => 'success',
      'data'     => $event,
  ];
});

Route::post('getflights', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['data']['event']['id'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];

    if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'event not found',
      ];
    }
    if (!(App\FLIGHT::where('event_id', $data['event']['id'])->exists())) {
        return [
        'response' => 'success',
        'data'     => null,
    ];
    }

    $query = App\FLIGHT::where('event_id', $data['event']['id'])->get();

    foreach ($query as $dat) {
        $flight[] = [
          'aircraft' => [
              'callsign' => $dat['aircraft_callsign'],
              'model'    => $dat['aircraft_model'],
          ],
          'flight' => [
              'id'   => $dat['flight_id'],
              'rule' => $dat['flight_rule'],
              'type' => $dat['flight_type'],
              'load' => $dat['flight_load'],
          ],
          'user' => [
              'division' => $dat['user_division'],
              'vid'      => $dat['user_vid'],
              'email'    => $dat['user_email'],
              'rating'   => $dat['user_rating'],
          ],
          'time' => [
              'departure' => $dat['time_departure'],
              'arrival'   => $dat['time_arrival'],
          ],
      ];
    }

    return [
      'response' => 'success',
      'data'     => $flight,
  ];
});

Route::post('getflight', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['data']['event']['id']) || empty($request['data']['flight']['id'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];

    if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'event not found',
      ];
    }
    if (!(App\FLIGHT::where('event_id', $data['event']['id'])->where('flight_id', $data['flight']['id'])->exists())) {
        return [
        'response' => 'error',
        'remark'   => 'flight not found',
    ];
    }

    $dat = App\FLIGHT::where('event_id', $data['event']['id'])->where('flight_id', $data['flight']['id'])->first();

    $flight = [
      'aircraft' => [
          'callsign' => $dat['aircraft_callsign'],
          'model'    => $dat['aircraft_model'],
      ],
      'flight' => [
          'id'   => $dat['flight_id'],
          'rule' => $dat['flight_rule'],
          'type' => $dat['flight_type'],
          'load' => $dat['flight_load'],
      ],
      'user' => [
          'division' => $dat['user_division'],
          'vid'      => $dat['user_vid'],
          'email'    => $dat['user_email'],
          'rating'   => $dat['user_rating'],
      ],
      'time' => [
          'departure' => $dat['time_departure'],
          'arrival'   => $dat['time_arrival'],
      ],
  ];

    return [
      'response' => 'success',
      'data'     => $flight,
  ];
});

Route::post('createevent', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['data']['event']['name']) || empty($request['data']['booking_time']['start']) || empty($request['data']['booking_time']['end']) || empty($request['data']['airport']['departure']) || empty($request['data']['airport']['arrival'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];
    $event_id = str_random(64);

    $event = new App\EVENT();
    $event->token = $request['access_key'];
    $event->event_id = $event_id;
    $event->event_name = $data['event']['name'];
    $event->booktime_start = $data['booking_time']['start'];
    $event->booktime_end = $data['booking_time']['end'];
    $event->airport_departure = $data['airport']['departure'];
    $event->airport_arrival = $data['airport']['arrival'];
    $event->save();

    return [
      'response' => 'success',
      'data'     => [
          'event' => [
              'id'   => $event_id,
              'name' => $data['event']['name'],
          ],
      ],
  ];
});

// Route::post('createflight', function (Request $request) {
//     $request = $request->json()->all();

//     if (empty($request['access_key']) || empty($request['data']['event']['id']) || empty($request['data']['aircraft']['callsign'])) {
//         return [
//           'response' => 'error',
//           'remark'   => 'missing some/all payload',
//       ];
//     }

//     if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
//         return [
//           'response' => 'error',
//           'remark'   => 'access denined',
//       ];
//     }

//     $data = $request['data'];
//     $flight_id = str_random(128);

//     if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
//         return [
//           'response' => 'error',
//           'remark'   => 'event not found',
//       ];
//     }

//     if (App\FLIGHT::where('aircraft_callsign', $data['aircraft']['callsign'])->where('event_id', $data['event']['id'])->exists()) {
//         return [
//       'response' => 'error',
//       'remark'   => 'callsign is already existed',
//     ];
//     }

//     $flight = new App\FLIGHT();
//     $flight->event_id = $data['event']['id'];
//     $flight->flight_id = $flight_id;
//     $flight->aircraft_callsign = $data['aircraft']['callsign'];
//     $flight->save();

//     return [
//       'response' => 'success',
//       'data'     => [
//           'flight' => [
//               'id'       => $flight_id,
//               'aircraft' => [
//                 'callsign' => $data['aircraft']['callsign'],
//               ],
//           ],
//       ],
//   ];
// });

Route::post('reserveflight', function (Request $request) {
    $request = $request->json()->all();

    App\CONFIRMPOOL::where('ticket_timeout', '<', Carbon\Carbon::now())->delete();

    if (empty($request['access_key']) || empty($request['data']['event']['id']) || empty($request['data']['user']['division']) || empty($request['data']['user']['vid']) || empty($request['data']['user']['email']) || empty($request['data']['user']['rating']) || empty($request['data']['aircraft']['callsign']) || empty($request['data']['aircraft']['model']) || empty($request['data']['flight']['rule']) || empty($request['data']['flight']['type']) || empty($request['data']['flight']['load']) || empty($request['data']['time']['departure']) || empty($request['data']['time']['arrival'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];
    $code = str_random(256);

    if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'event not found',
      ];
    }

    $event_booking_time = App\EVENT::select('booktime_start', 'booktime_end')->where('token', $request['access_key'])->where('event_id', $data['event']['id'])->first();

    if (Carbon\Carbon::parse($event_booking_time['booktime_start'])->isFuture()) {
        return [
          'response' => 'error',
          'remark'   => 'booking is not start yet',
      ];
    }
    if (Carbon\Carbon::parse($event_booking_time['booktime_end'])->isPast()) {
        return [
          'response' => 'error',
          'remark'   => 'booking is expired',
      ];
    }

    if (App\FLIGHT::where('user_vid', $data['user']['vid'])->where('event_id', $data['event']['id'])->exists()) {
        return [
        'response' => 'error',
        'remark'   => 'you already reserved flight for this event',
    ];
    }
    if (App\CONFIRMPOOL::where('user_vid', $data['user']['vid'])->where('event_id', $data['event']['id'])->exists()) {
        return [
        'response' => 'error',
        'remark'   => 'you already send confirmation email! please check your mailbox or wait 30 minutes to make ticket timed out',
    ];
    }

    $check = App\FLIGHT::select('user_vid')->where('event_id', $data['event']['id'])->where('aircraft_callsign', $data['aircraft']['callsign'])->first();
    if ($check['user_vid'] != null) {
        return [
        'response' => 'error',
        'remark'   => 'callsign duplicated',
    ];
    }

    $pool = new App\CONFIRMPOOL();
    $pool->ticket_code = $code;
    $pool->ticket_timeout = Carbon\Carbon::now()->addMinutes(30);
    $pool->event_id = $data['event']['id'];
    $pool->flight_id = $data['flight']['id'];
    $pool->user_division = $data['user']['division'];
    $pool->user_vid = $data['user']['vid'];
    $pool->user_email = $data['user']['email'];
    $pool->user_rating = $data['user']['rating'];
    $pool->aircraft_callsign = $data['aircraft']['callsign'];
    $pool->aircraft_model = $data['aircraft']['model'];
    $pool->flight_rule = $data['flight']['rule'];
    $pool->flight_type = $data['flight']['type'];
    $pool->flight_load = $data['flight']['load'];
    $pool->time_departure = $data['time']['departure'];
    $pool->time_arrival = $data['time']['arrival'];
    $pool->save();

    $tmp = App\EVENT::select('token')->where('event_id', $data['event']['id'])->first();
    $hosted_division = App\ACCESS::select('division')->where('key', $tmp['token'])->first();

    $senddata = [
      'division' => strtolower($hosted_division['division']),
      'code'     => $code,
  ];

    Mail::to($data['user']['email'])->send(new App\Mail\ConfirmEmail($senddata));

    return [
      'response' => 'success',
      'remark'   => 'verification email sent (may take 1-3 minutes)',
  ];
});

Route::delete('deleteevent', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['data']['event']['id'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];

    if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'no event found',
      ];
    }

    App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->delete();
    App\FLIGHT::where('event_id', $data['event']['id'])->delete();
    App\CONFIRMPOOL::where('event_id', $data['event']['id'])->delete();

    return [
      'response' => 'success',
  ];
});

Route::delete('deleteflight', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['data']['event']['id']) || empty($request['data']['flight']['id'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];

    if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'no event found',
      ];
    }

    if (!(App\FLIGHT::where('event_id', $data['event']['id'])->where('flight_id', $data['flight']['id'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'no flight found',
      ];
    }

    App\FLIGHT::where('event_id', $data['event']['id'])->where('flight_id', $data['flight']['id'])->delete();
    App\CONFIRMPOOL::where('event_id', $data['event']['id'])->where('flight_id', $data['flight']['id'])->delete();

    return [
      'response' => 'success',
  ];
});

Route::patch('updateevent', function (Request $request) {
    $request = $request->json()->all();

    if (empty($request['access_key']) || empty($request['data']['event']['id'])) {
        return [
          'response' => 'error',
          'remark'   => 'missing some/all payload',
      ];
    }

    if (!(App\ACCESS::where('key', $request['access_key'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'access denined',
      ];
    }

    $data = $request['data'];

    if (!(App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->exists())) {
        return [
          'response' => 'error',
          'remark'   => 'no event found',
      ];
    }

    if (!(empty($data['event']['name']))) {
        $update['event_name'] = $data['event']['name'];
    }

    if (!(empty($data['booking_time']['start']))) {
        $update['booktime_start'] = $data['booking_time']['start'];
    }

    if (!(empty($data['booking_time']['end']))) {
        $update['booktime_end'] = $data['booking_time']['end'];
    }

    if (!(empty($data['airport']['departure']))) {
        $update['airport_departure'] = $data['airport']['departure'];
    }

    if (!(empty($data['airport']['arrival']))) {
        $update['airport_arrival'] = $data['airport']['arrival'];
    }

    if (!isset($update)) {
        return [
          'response' => 'error',
          'remark'   => 'nothing need to update',
      ];
    }

    if (App\EVENT::where('token', $request['access_key'])->where('event_id', $data['event']['id'])->update($update)) {
        return [
          'response' => 'success',
      ];
    } else {
        return [
            'response' => 'error',
            'remark'   => 'unexpected error has occred',
        ];
    }
});
