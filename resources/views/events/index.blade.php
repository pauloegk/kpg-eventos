@extends('layouts.app')

@section('content')
<div class="container">
    @if (count($events) > 0)
        <div class="row div-new-event">
            <div class="col-6 title">
                Eventos disponíveis
            </div>
            <div class="col-6 text-right">
                <a class="btn btn-primary" href="{{ route('events.create') }}" role="button">Novo evento</a>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">

            @if(session()->get('success'))
                <div class="alert alert-success">
                {{ session()->get('success') }}
                </div>
            @endif

            @if(session()->get('error'))
                <div class="alert alert-danger">
                {{ session()->get('error') }}
                </div>
            @endif

            @if (count($events) > 0)
                @foreach ($events as $event)
                    <div class="card margin-bottom">
                        <div class="card-body">
                            <div class="card-title">
                                <span class="name">{{ $event->name }}</span>
                                @if ($event->canceled == 1)
                                    - <span class="canceled">Cancelado</span>
                                @endif
                            </div>

                            <h6 class="card-subtitle mb-2">Data: {{ date('d/m/Y', strtotime($event->date_event))}}</h6>

                            <div>
                                <span class="card-text">Evento criado por: {{ $event->ownerUser->name}}</span>
                            </div>

                            <p class="card-text">{{ count($event->guests)}} convidado(s)</p>

                            <div class="button-details">
                                <a href="{{ route('events.show', $event->id)}}" class="card-link">Ver detalhes</a>
                            </div>

                        </div>
                    </div>
                @endforeach
            @else
               <div class="no-events-results">
                    <span> Nenhum evento cadastrado até o momento! </span>
               </div>
               <div class="text-center">
                    <a class="btn btn-link" href="{{ route('events.create') }}">Clique aqui para cadastrar.</a>
               </div>
            @endif

        </div>
    </div>
</div>

@endsection
