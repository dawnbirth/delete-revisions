<div class="wrap" style="direction:ltr">

    
    <div style="padding:20px;border:1px solid #dcdcdc;font-size:1.2em;color:#d26800;">
        <h1>Delete Old Revisions, Process started...</h1>
        Please wait until Completion message appears.
        <hr>
        <div id="response" style="max-height:300px;overflow:auto;">

        </div>
    </div>
    <script>
        var status = 1;
        var makeRequest = () => {
            if ( status != 1 ) return;

            jQuery.ajax({
                url: ajaxurl,
                data: {"action": "delete_revisions", "security" : "<?= $ajax_nonce; ?>" },
                type:"POST",
                beforeSend: () => {
                    status = 0;
                },
                success: ( data ) => {

                    var obj    = data.data;
                    var length = obj.length;
                    var output = '';

                    if ( length ) {

                        for( i=0; i < length; i++ ) {
                            var deleted = 'Deleted';
                            var style = 'color:green;';
                            if ( ! obj[i].deleted ) {
                                deleted = 'Error';
                                style="color:red;";
                            }
                            output += '<div style="' + style + '">' + obj[i].id + ' <strong>' + deleted + '</strong></div>';
                        }

                        jQuery("#response").append(output);

                    } else {
                        jQuery("#response").html(data.msg);
                    }

                    if ( data.action == "next" )
                        status = 1;
                    
                }
            });
        }
        var startNow = setTimeout( function tick() {
        
            makeRequest();
            startNow = setTimeout( tick, 0 );

        }, 0 );
    </script>

</div>