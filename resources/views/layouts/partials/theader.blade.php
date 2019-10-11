<thead bgcolor="#dc3545">
@foreach($arg as $item)
  <th class="text-{{ strtolower($item['align']) }}">
    <span style="color:whitesmoke; font-weight: bold">
      {{ strtoupper($item['text']) }}
    </span>
  </th>
@endforeach
<th class="text-center">
  <span style="color:whitesmoke; font-weight: bold">
    CONTROL
  </span>
</th>
</thead>
