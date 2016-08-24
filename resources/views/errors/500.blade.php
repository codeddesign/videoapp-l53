<div class="content">
    <div class="title">Something went wrong.</div>
    @unless(empty($sentryID))
    <!-- Sentry JS SDK 2.1.+ required -->
        <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

        <script>
            Raven.showReportDialog({
                eventId: '{{ $sentryID }}',

                // use the public DSN (dont include your secret!)
                dsn: 'https://2612feb24a7342a5ba960eaecb41803f@app.getsentry.com/93921'
            });
        </script>
    @endunless
</div>
