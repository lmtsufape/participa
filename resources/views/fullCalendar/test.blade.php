<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />

<style>

  body {
    margin-top: 40px;
    font-size: 14px;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
  }

  #external-events {
    position: fixed;
    left: 20px;
    top: 20px;
    width: 150px;
    padding: 0 10px;
    border: 1px solid #ccc;
    background: #eee;
    text-align: left;
  }

  #external-events h4 {
    font-size: 16px;
    margin-top: 0;
    padding-top: 1em;
  }

  #external-events .fc-event {
    margin: 3px 0;
    cursor: move;
  }

  #external-events p {
    margin: 1.5em 0;
    font-size: 11px;
    color: #666;
  }

  #external-events p input {
    margin: 0;
    vertical-align: middle;
  }

  #calendar-wrap {
    margin-left: 200px;
  }

  #calendar {
    max-width: 1100px;
    margin: 0 auto;
  }

</style>
</head>
<body>
  <div id='wrap'>

    {{-- <div id='external-events'>
      <h4>Draggable Events</h4>

      <div id='external-events-list'>
        <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
          <div class='fc-event-main'>My Event 1</div>
        </div>
        <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
          <div class='fc-event-main'>My Event 2</div>
        </div>
        <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
          <div class='fc-event-main'>My Event 3</div>
        </div>
        <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
          <div class='fc-event-main'>My Event 4</div>
        </div>
        <div class='fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event'>
          <div class='fc-event-main'>My Event 5</div>
        </div>
      </div>

      <p>
        <input type='checkbox' id='drop-remove' />
        <label for='drop-remove'>remove after drop</label>
      </p>
    </div> --}}



  </div>
</body>
</html>
