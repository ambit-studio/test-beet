<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Profit and loss</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ url('styles.css') }}">

</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    @if (isset($report))
                    <li><a href="/">Start page </a></li>
                    <li class="active"><a href="/report">Report</a></li>
                    @else
                    <li class="active"><a href="/">Start page </a></li>
                    <li><a href="/report">Report</a></li>
                    @endif
                </ul>
                <div class="navbar-brand navbar-right">
                    by Vladimir Voznyi
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('file') }}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Please upload your php file</label>
                                <div class="col-sm-6">
                                    <input type="file" name="file" class="form-control"></input>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                            </div>
                        </form>                    
                    </div>
                </div>

                @if (isset($report))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Financial report. Profit and loss.</h4>
                    </div>

                    <table class="table">
                        <tr>
                            <th></th>
                            @foreach ($data_collection as $data)
                            <th>
                                @if ($data->month_id == 1)
                                <div>{{ $data->year }}</div>
                                @else
                                <div>-</div>
                                @endif
                                {{ $data->month->title }}
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="active">Revenue</td>
                            @foreach ($data_collection as $data)
                            <td>{{ $data->revenue }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="active">Cost</td>
                            @foreach ($data_collection as $data)
                            <td>{{ $data->cost }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="success">Profit</td>
                            @foreach ($data_collection as $data)
                            <td class="info">{{ $data->profit }}</td>
                            @endforeach
                        </tr>

                        <tr></tr>

                        <tr>
                            <td></td>
                            @foreach ($profit_percentage as $percentage)
                            <td>
                                @if ($percentage > 0)
                                <div style="height:{{ $percentage }}%;"></div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td></td>
                            @foreach ($profit_percentage as $percentage)
                            <td>
                                @if ($percentage < 0)
                                <div style="height:{{ abs($percentage) }}%;"></div>
                                @endif
                            </td>
                            @endforeach
                        </tr>

                    </table>
                </div>
                @else
                <div class="jumbotron">
                    <h3>Welcome!</h3>
                    <p>Here you can upload your php file from your program (with the same structure as your example php file has), and application will form report for you.</p>
                    <div>By the way, all data will be stored in database, but only last six month will be shown.</div>
                    <div>And if you want to refresh report - upload new php file, all old data will be deleted and new report will be formed.</div>
                </div>
                @endif
            </div>
        </div>
    </div>



    <!-- MODAL WINDOW -->

    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">
                <i class="fa fa-times"></i>
            </span></button>
            <h5 class="modal-title"></h5>
          </div>
          <div class="modal-body">
            <p id="message"></p>
          </div>
        </div>
      </div>
    </div>

    <div class="message_box">@if (session('message')) {!! session('message') !!} @endif </div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <script type="text/javascript">

        $(window).load( function() {
             
            showModal()

            function showModal() {
                message = $('.message_box').html();
                if (message.length > 3) {
                    $('#message').html(message);
                    $('#myModal').modal();
                }
                    
            }
        });

    </script>
</body>
</html>
