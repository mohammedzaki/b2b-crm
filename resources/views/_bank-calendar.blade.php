<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-body">
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

@section('styles')
    <style>
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .fc-list-event-time {
            display:none;
        }
    </style>
@endsection
@section('scripts')
    <script>
        $(function () {

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear()

            var Calendar = FullCalendar.Calendar;

            var calendarEl = document.getElementById('calendar');

            // initialize the external events
            // -----------------------------------------------------------------



            var calendar = new Calendar(calendarEl, {
                initialView: 'listYear',
                headerToolbar: {
                    start: 'title', // will normally be on the left. if RTL, will be on the right
                    center: 'dayGridMonth,listMonth,listYear',
                    end: 'today prevYear,prev,next,nextYear' // will normally be on the right. if RTL, will be on the left
                },

                themeSystem: 'bootstrap',
                locale: 'ar',
                direction: 'rtl',
                firstDay: 6,
                buttonText: {
                    today: 'الشهر الحالي',
                    dayGridMonth: 'عرض شبكي',
                    listMonth: 'عرض اجندة',
                    listYear: 'عرض الكل'
                },
                eventDidMount: function(info) {
                    $(info.el).tooltip({
                        title: info.event.extendedProps.description,
                        container: 'body',
                        delay: { "show": 50, "hide": 50 }
                    });
                },

                //Random default events
                events: [

                ],

                eventSources: [

                    // your event source
                    {
                        url: '/getCalendarItems',
                        method: 'GET',
                        extraParams: {
                            custom_param1: 'something',
                            custom_param2: 'somethingelse'
                        },
                        failure: function() {
                            alert('there was an error while fetching events!');
                        },
                        color: 'yellow'
                    }

                    // any other sources...

                ],
                editable  : false,
                droppable : false,

            });

            calendar.render();
            // $('#calendar').fullCalendar()
        })
    </script>
@endsection