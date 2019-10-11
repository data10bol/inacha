@extends('layouts.master')
@section('content')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <b>USUARIO: {{ strtoupper($user->name) }}</b>
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div>
                <div class="row">
                  @component('layouts.partials.exportcontrol',[
                      'id' => $user->id,
                      'excel' => false,
                      'urlexcel' => "#",
                      'word' => false,
                      'urlword' => "#",
                      'pdf' => false,
                      'urlpdf' => ''.Request::url().'-pdf',
                      ])
                  @endcomponent
                  @component('layouts.partials.hcontrol',[
                      'id' => $user->id,
                      'url1' => $data["url1"],
                      'url2' => $data["url2"],
                      'type' => 'show',
                      'add' => false,
                      'del' => true,
                      'editenable' => true,
                  ])

                  @endcomponent
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-bordered table-condensed dataTable"
                     role="grid"
                     aria-describedby="main table">
                <tr>
                  <th class="align-top text-left" style="width: 120px">ID</th>
                  <td class="align-top text-justify">
                    {{ $user->id }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">ESTADO</th>
                  <td class="align-top text-justify">
                    @statusvar($user->status)
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">C.I.</th>
                  <td class="align-top text-justify">
                    {{ $user->employee }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">USERNAME</th>
                  <td class="align-top text-justify">
                    {{ $user->username }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">NOMBRE</th>
                  <td class="align-top text-justify">
                    {{ $user->name }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">CARGO</th>
                  <td class="align-top text-justify">
                    {{ $user->position->name }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">DEPARTAMENTO</th>
                  <td class="align-top text-justify">
                    {{ $user->position->department->name }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">DEPENDE</th>
                  <td class="align-top text-justify">
                    @php $depend= App\User::Select('name')->Where('id',$user->dependency)->first();;
                    @endphp {{ $depend->name }}
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">POAI</th>
                  <td class="align-top text-justify">
                    @php $plans= App\Plan::Select('name')
                                                ->Where('position_id',$user->position_id)
                                                ->pluck('name')->toarray();
                    @endphp
                    <ul>
                      @forelse ($plans as $plan)
                        <li>{{ $plan }}</li>
                      @empty
                        <li><p class="text-danger">SIN DATOS</p></li>
                      @endforelse
                    </ul>
                  </td>
                </tr>
                <tr>
                  <th class="align-top text-left">ROLES</th>
                  <td class="align-top text-justify">
                    <ul>
                      @foreach($user->getRoleNames() as $role)
                        <li>{{ $role }} </li>
                      @endforeach
                    </ul>
                  </td>
                </tr>
              </table>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
@endsection
