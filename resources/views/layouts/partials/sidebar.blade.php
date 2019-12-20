@php
  if(!isset($data["active"]))
      $data["active"] = "dashboard";
@endphp
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
      <img
        src="{{ Avatar::create( auth()->user()->name )->setBackground('#da2031')->setBorder(1, '#da2031')->toBase64() }}"
        class="img-circle elevation-2" alt="{{ auth()->user()->name }}">
    </div>
    <div class="info">
      <a href="#" class="d-block">{{ auth()->user()->name }}</a>
      @foreach( auth()->user()->getRoleNames() as $role)
        <font color="red">{{ $role }}</font>
      @endforeach
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      <li class="nav-item">
        <a href="/dashboard" class="nav-link @if($data["active"] == 'dashboard') active @endif">
          <i class="nav-icon fa fa-dashboard"></i>
          <p>
            Inicio
          </p>
        </a>
      </li>
      @hasanyrole('Supervisor|Administrador')
      <li class="nav-header">PLANIFICACIÓN PLURIANUAL</li>
      <li class="nav-item has-treeview
                @if($data["active"] == 'period' ||
                    $data["active"] == 'year' ||
                    $data["active"] == 'policy' ||
                    $data["active"] == 'target' ||
                    $data["active"] == 'result' ||
                    $data["active"] == 'doing')
        menu-open
@endif">
        <a href="#" class="nav-link
                @if($data["active"] == 'period' ||
                    $data["active"] == 'year' ||
                    $data["active"] == 'policy' ||
                    $data["active"] == 'target' ||
                    $data["active"] == 'result' ||
                    $data["active"] == 'doing')
          active
@endif">
          <i class="nav-icon fa fa-building"></i>
          <p>
            Gubernamental
            <i class="fa fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('period.index') }}" class="nav-link @if($data["active"] == 'period') active @endif">
              <i class="fa fa-clock-o nav-icon"></i>
              <p>Periodos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('year.index') }}" class="nav-link @if($data["active"] == 'year') active @endif">
              <i class="fa fa-puzzle-piece nav-icon"></i>
              <p>Gestiones</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('policy.index') }}" class="nav-link @if($data["active"] == 'policy') active @endif">
              <i class="fa fa-cubes nav-icon"></i>
              <p>Pilares</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('target.index') }}" class="nav-link @if($data["active"] == 'target') active @endif">
              <i class="fa fa-flag-checkered nav-icon"></i>
              <p>Metas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('result.index') }}" class="nav-link @if($data["active"] == 'result') active @endif">
              <i class="fa fa-star nav-icon"></i>
              <p>Resultados</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('doing.index') }}" class="nav-link @if($data["active"] == 'doing') active @endif">
              <i class="fa fa-paper-plane nav-icon"></i>
              <p>Acciones</p>
            </a>
          </li>
        </ul>
      </li>
      @endrole
      @hasanyrole('Usuario|Responsable|Supervisor|Administrador')
      <li class="nav-item has-treeview
                @if($data["active"] == 'configuration' ||
                    $data["active"] == 'goal')
        menu-open
@endif">
        <a href="#" class="nav-link
              @if($data["active"] == 'configuration' ||
                    $data["active"] == 'goal')
          active
@endif">
          <i class="nav-icon fa fa-university"></i>
          <p>
            Institucional
            <i class="fa fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('configuration.index') }}"
               class="nav-link @if($data["active"] == 'configuration') active @endif">
              <i class="fa fa-pencil nav-icon"></i>
              <p>Definición</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('goal.index') }}" class="nav-link @if($data["active"] == 'goal') active @endif">
              <i class="fa fa-pencil-square-o nav-icon"></i>
              <p>A. Mediano Plazo</p>
            </a>
          </li>
        </ul>
      </li>
      @endrole
      <li class="nav-header">PLANIFICACIÓN ANUAL</li>
      @hasanyrole('Usuario|Responsable|Supervisor|Administrador')
      <li class="nav-item has-treeview
                @if($data["active"] == 'action' ||
                    $data["active"] == 'operation' ||
                    $data["active"] == 'task' ||
                    $data["active"] == 'subtask')
        menu-open
@endif">
        <a href="#" class="nav-link
                @if($data["active"] == 'action' ||
                    $data["active"] == 'operation' ||
                    $data["active"] == 'task' ||
                    $data["active"] == 'subtask')
          active
@endif">
          <i class="nav-icon fa fa-book"></i>
          <p>
            Formulación POA
            <i class="fa fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @hasanyrole('Supervisor|Administrador')
          <li class="nav-item">
            <a href="{{ route('action.index') }}" class="nav-link @if($data["active"] == 'action') active @endif">
              <i class="fa fa-thumb-tack nav-icon"></i>
              <p>A. Corto Plazo</p>
            </a>
          </li>
          @endrole
          @hasanyrole('Usuario|Responsable|Supervisor|Administrador')
          <li class="nav-item">
            <a href="{{ route('operation.index') }}" class="nav-link @if($data["active"] == 'operation') active @endif">
              <i class="fa fa-tags nav-icon"></i>
              <p class="text">Operaciones</p>
            </a>
          </li>
          @endrole
          <li class="nav-item">
            <a href="{{ route('task.index') }}" class="nav-link @if($data["active"] == 'task') active @endif">
              <i class="fa fa-tasks nav-icon"></i>
              <p class="text">Tareas</p>
            </a>
          </li>
        </ul>
      </li>
      @endrole
      @hasanyrole('Usuario|Responsable|Supervisor|Administrador')
      <li class="nav-item has-treeview
                @if($data["active"] == 'execution.action' ||
                    $data["active"] == 'execution.operation' ||
                    $data["active"] == 'execution.task')
        menu-open
@endif">
        <a href="#" class="nav-link
                @if($data["active"] == 'execution.action' ||
                    $data["active"] == 'execution.operation' ||
                    $data["active"] == 'execution.task')
          active
@endif">
          <i class="nav-icon fa fa-edit"></i>
          <p>
            Ejecución POA
            <i class="fa fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @hasanyrole('Responsable|Supervisor|Administrador')
          <li class="nav-item">
            <a href="{{ route('executionaction.index') }}"
               class="nav-link @if($data["active"] == 'execution.action') active @endif">
              <i class="fa fa-thumb-tack nav-icon"></i>
              <p class="text">Acciones</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('executionoperation.index') }}"
               class="nav-link @if($data["active"] == 'execution.operation') active @endif">
              <i class="fa fa-tags nav-icon"></i>
              <p class="text">Operaciones</p>
            </a>
          </li>
          @endrole
          <li class="nav-item">
            <a href="{{ route('executiontask.index') }}"
               class="nav-link @if($data["active"] == 'execution.task') active @endif">
              <i class="fa fa-tasks nav-icon"></i>
              <p class="text">Tareas</p>
            </a>
          </li>
        </ul>
      </li>
      @endrole
      @hasanyrole('Responsable|Supervisor|Administrador|Usuario')
      <li class="nav-item has-treeview
                @if($data["active"] == 'report.poa' ||
                    $data["active"] == 'chart.poa' ||
                    $data["active"] == 'gantt.poa' ||
                    $data["active"] == 'list.poa' ||
                    $data["active"] == 'form.poa')
        menu-open
@endif">
        <a href="#" class="nav-link
                @if($data["active"] == 'report.poa' ||
                    $data["active"] == 'chart.poa' ||
                    $data["active"] == 'gantt.poa' ||
                    $data["active"] == 'list.poa' ||
                    $data["active"] == 'form.poa')
          active
@endif">
          <i class="nav-icon fa fa-pie-chart"></i>
          <p>
            Seguimiento
            <i class="right fa fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('ganttpoa') }}" class="nav-link" target="_blank">
              <i class="fa fa-line-chart nav-icon"></i>
              <p>Gantt</p>
            </a>
          </li>
          @hasanyrole('Supervisor|Administrador|Responsable')
          <li class="nav-item">
            <a href="{{ route('reportpoa') }}"
               class="nav-link @if($data["active"] == 'report.poa') active @endif">
              <i class="fa fa-file-o nav-icon"></i>
              <p>Reporte</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('chartpoa') }}"
               class="nav-link @if($data["active"] == 'chart.poa') active @endif">
              <i class="fa fa-bar-chart nav-icon"></i>
              <p>Gráficos</p>
            </a>
          </li>
          @hasanyrole('Supervisor|Administrador')
          <li class="nav-item">
            <a href="{{ route('formpoa') }}" class="nav-link @if($data["active"] == 'form.poa') active @endif">
              <i class="fa fa-file-excel-o nav-icon"></i>
              <p>Formularios</p>
            </a>
          </li>
          @endrole
        @endrole
        </ul>
      </li>
      @endrole
      @hasanyrole('Supervisor|Administrador')
        <li class="nav-header">HERRAMIENTAS</li>

        <li class="nav-item has-treeview
                  @if($data["active"] == 'department' ||
                      $data["active"] == 'position' ||
                      $data["active"] == 'plan' ||
                      $data["active"] == 'user' ||
                      $data["active"] == 'role' ||
                      $data["active"] == 'permission' ||
                      $data["active"] == 'limit'||
                      $data["active"] == 'events')
          menu-open
  @endif">
          <a href="#" class="nav-link
                  @if($data["active"] == 'department' ||
                      $data["active"] == 'position' ||
                      $data["active"] == 'plan' ||
                      $data["active"] == 'user' ||
                      $data["active"] == 'role' ||
                      $data["active"] == 'permission' ||
                      $data["active"] == 'limit'||
                      $data["active"] == 'events')
            active
  @endif">
            <i class="nav-icon fa fa-cogs"></i>
            <p>
              Administrador
              <i class="fa fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('limit.index') }}" class="nav-link @if($data["active"] == 'limit') active @endif">
                <i class="fa fa-calendar-times-o nav-icon"></i>
                <p>Límites</p>
              </a>
            </li>
          @role('Administrador')
            <li class="nav-item">
              <a href="{{ route('department.index') }}"
                 class="nav-link @if($data["active"] == 'department') active @endif">
                <i class="fa fa-sitemap nav-icon"></i>
                <p>Departamentos</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('position.index') }}" class="nav-link @if($data["active"] == 'position') active @endif">
                <i class="fa fa-cubes nav-icon"></i>
                <p>Cargos</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('plan.index') }}" class="nav-link @if($data["active"] == 'plan') active @endif">
                <i class="fa fa-clipboard nav-icon"></i>
                <p>Planes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('user.index') }}" class="nav-link @if($data["active"] == 'user') active @endif">
                <i class="fa fa-users nav-icon"></i>
                <p>Usuarios</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('role.index') }}" class="nav-link @if($data["active"] == 'role') active @endif">
                <i class="fa fa-laptop nav-icon"></i>
                <p>Roles</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('permission.index') }}"
                 class="nav-link @if($data["active"] == 'permission') active @endif">
                <i class="fa fa-check-square-o nav-icon"></i>
                <p>Permisos</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('events.index') }}" class="nav-link @if($data["active"] == 'events') active @endif">
                <i class="fa fa-spinner nav-icon"></i>
                <p>Registros</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/logs" class="nav-link" target="_blank">
                <i class="fa fa-exclamation-triangle nav-icon"></i>
                <p>Errores</p>
              </a>
            </li>
            
          </ul>
        </li>
        @endrole
      @endrole
      <li class="nav-header">DOCUMENTOS</li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fa fa-file"></i>
          <p>Manual de Usuario</p>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
