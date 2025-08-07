<!-- foot.php -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
    const pageLoader = `
            <div id="uniquePageLoader" style="position: fixed; z-index: 99999999; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); top: 0; left: 0; display: flex; align-items: center; justify-content: center">
                <style>body { overflow: hidden; }</style>
                <div style="max-width: 150px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="margin: auto; display: block;" width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                    <circle cx="50" cy="50" r="0" fill="none" stroke="#e90c59" stroke-width="2">
                    <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="0s"></animate>
                    <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="0s"></animate>
                    </circle>
                    <circle cx="50" cy="50" r="0" fill="none" stroke="#46dff0" stroke-width="2">
                    <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="-0.5s"></animate>
                    <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="-0.5s"></animate>
                    </circle>
                </svg>
                </div>
            </div>
            `;

    // ✅ Reusable AJAX form submission function
    function submit_form_data_ajax(
        url,
        data,
        onComplete = function(output) {
            console.log(output);
        },
        onError = function(err) {
            console.error(err);
        }
    ) {
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                // Remove any existing loader first
                $('#uniquePageLoader').remove();
                $('body').prepend(pageLoader);
            },
            success: function(data) {
                onComplete(data);
            },
            error: function(xhr, status, err) {
                onError(err);
            },
            complete: function() {
                $('#uniquePageLoader').remove();
            }
        });
    }

    function submit_form_data_ajax_without_loader(
        url,
        data,
        onComplete = function(output) {
            console.log(output);
        },
        onError = function(err) {
            console.error(err);
        }
    ) {
        /* ajax function */
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                onComplete(data);
            },
            error: function(err) {
                onError(err);
            },
        });
    }
</script>