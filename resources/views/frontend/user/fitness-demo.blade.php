<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Fitness Demo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="description" content=""/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7
/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" href="favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .band-a {
            width: 100%;
            /*height: 5rem;*/
            background-color: #EFE5DA;
            border-radius: 2.5rem;
            /*margin-top: 45%;*/
            /*margin-bottom: auto;*/
            /*margin-left: 1rem;*/
            /*margin-right: 1rem;*/
            /*display: flex;*/
            /*flex-flow: row wrap;*/
            /*justify-content: space-around;*/
        }

        .orange-circle {
            background-color: orange;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;

        }

        .btn-circle.btn-xl {
            background-color: orange;
            width: 70px;
            height: 70px;
            padding: 10px 16px;
            border-radius: 35px;
            font-size: 24px;
            line-height: 1.33;
            color: white;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;
        }
    </style>
</head>
<body>
<div class="container">
    {{--    <div class="row mt-5">--}}
    {{--        <div class="col-sm-6">--}}
    {{--            <div class="card" style="background-color: #F7F0E9; color: #403333">--}}
    {{--                <div class="card-header">--}}
    {{--                    <div class="row justify-content-center text-center">--}}
    {{--                        <span>Tracking<br>Now</span>--}}
    {{--                    </div>--}}
    {{--                    <div class="row justify-content-center mt-3">--}}
    {{--                        <h2 style=""><strong>00:18:16</strong></h2>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="card-body">--}}


    {{--                    @for($i = 1; $i <= 4; $i++)--}}
    {{--                        <div class=" d-flex justify-content-between align-items-center">--}}
    {{--                            <span>{{ rand(1, 5) }}d ago</span>--}}
    {{--                            <span>{{ rand(8, 15) }}.{{ rand(10, 50) }}km</span>--}}
    {{--                        </div>--}}
    {{--                        @if($i < 4)--}}
    {{--                            <hr>--}}
    {{--                        @endif--}}
    {{--                    @endfor--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

{{--    <div class="band-a" style="">--}}
{{--        <div class="orange-circle">--}}
{{--            <i class="fa-solid fa-arrow-up" style="margin: auto;"></i>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="row mt-5">
        <div class="band-a row p-3" style="">
            <div class="col-2">
                <div class="btn-circle btn-xl">
                    <i class="fa-solid fa-arrow-up" style="margin: auto;"></i>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7
/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>
