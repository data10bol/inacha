<!DOCTYPE html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">

  <script src="/js/gantt/edge/dhtmlxgantt.js"></script>
  <link href="/js/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
  <script src="/js/gantt/ext/dhtmlxgantt_marker.js"></script>
  <script src="/js/gantt/edge/locale/locale_es.js" charset="utf-8"></script>
  <script src="/js/gantt/edge/api.js"></script>

  <style type="text/css">

    html, body {
      height: 100%;
      padding: 0px;
      margin: 0px;
      overflow: hidden;
    }
    .gantt_grid_scale .gantt_grid_head_cell,
    .gantt_task .gantt_task_scale .gantt_scale_cell {
      font-weight: bold;
      font-size: 14px;
      color: rgba(0, 0, 0, 0.7);
    }

  </style>
</head>
<body>
<div id="gantt_here" style='width:100%; height:100%;'></div>
<script type="text/javascript">

  gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";
  gantt.config.scale_unit = "month";
  gantt.config.date_scale = "%F %Y";

  gantt.config.layout = {
    css: "gantt_container",
    rows: [
      {
        cols: [
          {view: "grid", width: 320, scrollY: "scrollVer"},
          {resizer: true, width: 1},
          {view: "timeline", scrollX: "scrollHor", scrollY: "scrollVer"},
          {resizer: true, width: 1},

        ]

      },
      {view: "scrollbar", id: "scrollHor", height: 20}
    ]
  };

  gantt.init("gantt_here");

  gantt.load("/api/gantt/{{ Hashids::encode(\Auth::user()->id) }}");


</script>
</body>
