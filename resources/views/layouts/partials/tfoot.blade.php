<tfoot bgcolor="#ebecec">
@foreach($arg as $item)
  <th class="text-{{ strtolower($item['align']) }}">
    <span style="color:black; font-weight: bold">
      {{ strtoupper($item['text']) }}
    </span>
  </th>
@endforeach
<th class="text-center">
  <span style="color:black; font-weight: bold">
    CONTROL
  </span>
</th>
</tfoot>
