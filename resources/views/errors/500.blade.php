@extends('layouts.app')

@section('title', 'خطأ')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">حدث خطأ بالبرنامج</h1>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="alert alert-danger">
            {{ $exception->getMessage() }}
        </div>
        <div class="alert alert-info">
            {{ link_to('/', 'اضغط هنا للرجوع الي الرئيسية') }}
        </div>
    </div>
    @if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
        <div class="subtitle">Error ID: {{ Sentry::getLastEventID() }}</div>

        <!-- Sentry JS SDK 2.1.+ required -->
        <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

        <script>
            Raven.showReportDialog({
                locale: 'ar-eg',
                eventId: '{{ Sentry::getLastEventID() }}',
                // use the public DSN (dont include your secret!)
                dsn: 'https://6647cd503d304743af251ab56bb4c216@sentry.io/222368',
                user: {
                    //'name': 'Jane Doe',
                    //'email': 'jane.doe@example.com',
                }
            });
        </script>
    @endif
@endsection