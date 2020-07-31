@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="alert" role="alert" id="alert" style="display:none;">
                <span id="msg"></span>
            </div>
            <event-details-component :event="{{ $event->toJson() }}"></event-details-component>
        </div>
    </div>
</div>
@endsection
