$(document).ready(function() {

$('#calendar').fullCalendar({
  events: [
    {
      title  : 'event1',
      start  : '2019-02-26'
    },
    {
      title  : 'event2',
      start  : '2019-02-26'
      end    : '2019-02-27'
    },
    {
      title  : 'event3',
      start  : '2010-01-09T12:30:00',
      allDay : false // will make the time show
    }
  ]
});
}