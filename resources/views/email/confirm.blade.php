<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Conconfirmation Email</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"> 
  <style>
    * {
      font-family: 'Open Sans', sans-serif;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col col-lg-2">
      </div>
      <div class="col-md-auto">
        <center><img src="https://rfe.ivaothai.com/logo/{{ $division }}.png" width="40%" /></center>
      </div>
      <div class="col col-lg-2">
      </div>
    </div>
    <div class="row">
      <div class="col">
      </div>
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Confirmation Required</h5>
            <p class="card-text">Please confirm by clicking the button below. (Note that link will be expired in 30 minutes.)</p>
            <a href="https://rfe.ivaothai.com/confirm/{{ $code }}" class="btn btn-primary">Confirm</a>
          </div>
        </div>
      </div>
      <div class="col">
      </div>
    </div>
    <div class="row align-items-end">
      <div class="col">
        <center><font color="#616161">©IVAO Thailand Division</font></center>
      </div>
    </div>
  </div>
</body>
</html>