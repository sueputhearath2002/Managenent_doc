@extends('layout')
@section('content')

    <div class="list-folders">
        <div class=" bg-light mt-3 ">
            <div class="d-flex align-i #6495edtems-center p-3 custom-bg">
                <span style="color: #ffffff">
                    <i class="fa-solid fa-folder fa-2x"></i>
                </span>
                <h6 class="ms-3 mt-2 text-white">{{ $name }}</h6>
            </div>
            @foreach ($imageStudent as $image)
                <div class="info d-flex justify-content-between p-3">
                    <div class="folder-user justify-content-start align-items-center d-flex">
                        <span style="color: #6495ed">
                            <img src="http://127.0.0.1:8000/storage/{{ $image->path }} " alt="Girl in a jacket"
                                width="50px" height="50px" />

                        </span>
                        <div class="text-name" style="margin-left: 14px ">
                            <a href="#">
                                {{ $image->path }}
                            </a>
                        </div>

                    </div>


                </div>

                @if ($loop->last == false)
                    <hr style="margin-left: 16px; margin-right: 16px; margin-top: 0px; margin-bottom: 0px">
                @endif
            @endforeach

        </div>

    </div>
@stop
