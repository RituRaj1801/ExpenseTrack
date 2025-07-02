<script>
    var pageLoader =
        '<div id="uniquePageLoader" style="position: fixed;z-index: 99999999;width: 100%;height: 100%;background-color: rgb(0 0 0 / 80%);top: 0;left: 0;display: flex;align-items: center;justify-content: center"><style>body{overflow: hidden;}</style><div class="ctbx"><div class="imglbx" style="max-width: 150px;"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" ><circle cx="50" cy="50" r="0" fill="none" stroke="#e90c59" stroke-width="2"><animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="0s"></animate><animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="0s"></animate></circle><circle cx="50" cy="50" r="0" fill="none" stroke="#46dff0" stroke-width="2"><animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="-0.5s"></animate><animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="-0.5s"></animate></circle></svg></div>';

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
        /* ajax function */
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $("body").prepend(pageLoader);
            },
            success: function(data) {
                onComplete(data);
            },
            error: function(err) {
                onError(err);
            },
            complete: function() {
                $("#uniquePageLoader").remove();
            },
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