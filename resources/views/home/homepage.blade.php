{{-- @extends('layout')
@section('content') --}}

<div class="list-folders">
    <div class=" bg-light mt-3 ">
        @foreach ($students as $student)
            <div class="info d-flex justify-content-between p-3">
                <div class="folder-user justify-content-start align-items-center d-flex">
                    <span style="color: #6495ed">
                        <i class="fa-solid fa-folder fa-2x"></i>
                    </span>
                    <div class="text-name" style="margin-left: 14px ">
                        <a href="{{ route("listImages",['student_id' => $student->id]) }}">
                                {{ $student->name }}
                        </a>
                    </div>

                </div>
                <button class="btn btn-outline-primary" onclick="window.location.href='{{ url('download-folder',['student_id' => $student->id]) }}'">
                    <i class="fa-solid fa-cloud-arrow-down me-1"></i>
                    <span>Download Folder</span>
                </button>
            </div>
            @if ($loop->last == false)
                 <hr style="margin-left: 16px ; margin-right:16px; margin-top:0px ; margin-bottom:0px">
            @endif

        @endforeach

    </div>
</div>
{{-- @stop --}}

