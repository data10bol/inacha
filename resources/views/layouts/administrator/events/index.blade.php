@extends('layouts.master')
@section('title', $title)
@section('content')
<section class="content">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
        <div class="card">
            <div class=" card-header">
                <h3>Eventos del sistema</h3>
                <hr>
                <a href="/administrator/events/configuration" class="btn btn-primary {{($model=='configuration')? 'active': null}}">Definición</a>
                <a href="/administrator/events/goal" class="btn btn-primary {{($model=='goal')? 'active': null}}">A. Mediano Plazo</a>
                <a href="/administrator/events/action" class="btn btn-primary {{($model=='action')? 'active': null}}">A. Corto Plazo</a>
                <a href="/administrator/events/definition" class="btn btn-primary {{($model=='definition')? 'active': null}}">Definiciones</a>
                <a href="/administrator/events/configuration" class="btn btn-primary">Operaciones</a>
                <a href="/administrator/events/configuration" class="btn btn-primary">Tareas</a>
                <a href="/administrator/events/configuration" class="btn btn-primary">Poas</a>
                <a href="/administrator/events/configuration" class="btn btn-primary">Limites</a>
                <a href="/administrator/events/configuration" class="btn btn-primary">Usuarios</a>

            </div>
            <div class="card-body">
                <ul>
                    @forelse ($audits as $audit)
                    <li>
                        @lang($model.'.updated.metadata', $audit->getMetadata())
                        @foreach ($audit->getModified() as $attribute => $modified)
                        <ul>
                            <li>@lang($model.'.'.$audit->event.'.modified.'.$attribute, $modified)</li>
                        </ul>
                        @endforeach
                    </li>
                    @empty
                    <p>@lang($model.'.unavailable_audits')</p>
                    @endforelse
                   
                </ul>
            </div>
        </div>
        </div>
    </div>
    </div>
</section>
@endsection