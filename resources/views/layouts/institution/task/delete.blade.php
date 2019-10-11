{!! Form::open([ 'method'=>'DELETE',
                  'url' => ['/institution/task', Hashids::encode($task->id)],
                  'style' => 'display:inline'])
!!}
<table>
    <tr>
      <th class="align-top text-left">
        CÓDIGO
      </th>
      <td class="align-top text-justify">
        <div class="col-sm-3">{{ $task->operation->action->goal->doing->result->target->policy->code }}.{{
            $task->operation->action->goal->doing->result->target->code }}.{{
            $task->operation->action->goal->doing->result->code }}.{{
            $task->operation->action->goal->doing->code }}.{{
            $task->operation->action->goal->code }}.{{
            $task->operation->action->code }}.{{
            $task->operation->code }}.{{
            $task->code }}
        </div>
      </td>
    </tr>
    <tr>
      <th class="align-top text-left">
        DESCRIPCIÓN
      </th>
      <td class="align-top text-justify">
        <div class="col-sm-12">
          {!! $task->definitions->first()->description !!}
        </div>
      </td>
    </tr>
    <tr>
        <th class="align-top text-left">
          {!! Form::label('reason', 'JUSTIFICATIVO', ['class' => 'col-sm-2 col-form-label']) !!}
        </th>
        <td class="align-top text-justify">
          <div class="col-sm-12">
            {!! Form::textarea('reason',
                      null,
                        ('' == 'required') ? [
                                'class' => 'form-control ckeditor',
                                'maxlength' => '512',
                                'required' => 'required']:[
                                'class' => 'form-control',
                                'maxlength' => '512'])
            !!}
          </div>
          <div class="col-sm-12">
            <label>
              <code>
                  {!! $errors->first('reason', '<p class="help-block">:message</p>') !!}
              </code>
            </label>
          </div>
        </td>
      </tr>
</table>
{!! Form::button('<i class="now-ui-icons ui-1_simple-remove"></i><b> Eliminar</b>',
          array('type' => 'submit',
                'class' => 'btn btn-danger btn-round btn-sm',
                'title' => 'Eliminar',
                'onclick'=>'return confirm("¿Desea eliminar la tarea?")' ))
!!}
{!! Form::close() !!}
