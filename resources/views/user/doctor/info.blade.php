@extends('user.layout')
@section('user_content')

    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Thông tin bác sĩ</h1>
            </div>
        </div>
    </div>

    <!-- Vendor End -->
    <!-- Open Content -->
    <section class="bg-light" style="background-color: white!important" id="article">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="container">
                        <img src=" @if (isset($doc->image)){{ asset('' . $doc->image) }} @else {{ asset('' . Config::get('app.image.default')) }} @endif" style="width:100%">
                    </div>
                </div>
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2">Họ và tên: {{ $doc->name }}</h1>
                            <ul class="list-unstyled d-flex justify-content-between">
                                <li class="text-right text-dark"
                                    style="font-weight: bold!important; font-size: 25px!important;">
                                    Chức vụ: {{ $doc->levelDoctor->name}}
                                </li>
                            </ul>
                            <h5>Mô tả:</h5>
                            <p> {{ $doc->description }} </p>
                            <h5>Chi tiết:</h5>
                            <p> {!! $doc->introduce !!} </p>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <style>
        body {
            font-family: Arial;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        img {
            vertical-align: middle;
        }

        /* Position the image container (needed to position the left and right arrows) */
        .container {
            position: relative;
        }

        /* Hide the images by default */
        .mySlides {
            display: none;
            border:black
        }

        /* Add a pointer when hovering over the thumbnail images */
        .cursor {
            cursor: pointer;
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 40%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            border-radius: 0 3px 3px 0;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* Container for image text */
        .caption-container {
            text-align: center;
            background-color: #222;
            padding: 2px 16px;
            color: white;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Six columns side by side */
        .column {
            float: left;
            width: 16.66%;
        }

        /* Add a transparency effect for thumnbail images */
        .demo {
            opacity: 0.6;
        }

        .active,
        .demo:hover {
            opacity: 1;
        }
    </style>
@endsection
