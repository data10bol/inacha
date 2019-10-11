@component('layouts.partials.pdfheader',[
    'arg'=>$header,
    'title' => "DEFINICIONES",
    ])
@endcomponent
  <tbody>
      @forelse($configuration as $item)
      <tr>
          <td class="align-top text-center" style="width: 100px">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {{ $item->period->start }} - {{ $item->period->finish }}
            </span>
          </td>
          <td class="align-top text-center" style="width: 100px">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              @statusvar($item->status)
              <br>Editar:
              @if($item->edit)
              <span class="badge badge-success">SI</span>
              @else
              <span class="badge badge-danger">NO</span>
              @endif
              <br>Refor:
              @if($item->reconfigure)
              <span class="badge badge-success">SI</span>
              @else
              <span class="badge badge-danger">NO</span>
              @endif
            </span>
          </td>
          <td class="align-top text-justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {!! $item->mission !!}
          </td>
          <td class="align-top text-justify">
            <span lang=ES-BO style='font-size:8.0pt;font-family:"Arial",sans-serif'>
              {!! $item->vision !!}
            </span>
          </td>
      </tr>
      @empty
          {!! emptyrecord(count($header)) !!}
      @endforelse
  </tbody>
</table>
</p>
</main>
</body>
