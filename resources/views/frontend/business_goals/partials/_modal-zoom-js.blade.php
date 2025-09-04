<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Listen for click events on zoom buttons
        document.querySelectorAll('.zoom-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the ID of the content to be zoomed
                let contentId = this.getAttribute('data-content-id');
                let report_title = this.getAttribute('data-report-title');
                let content = document.getElementById(contentId).innerHTML;

                // Inject the content into the modal
                document.getElementById('zoomModalContent').innerHTML = content;
                document.getElementById('zoomModalLabel').innerHTML = report_title;
            });
        });
    });

</script>
