<div id="system">
    <div class="grid-block">
        <div class="grid-block">
            <div class="grid-">
                <h1 class="title">My Profile</h1>
                <hr /><br />
            </div>
        </div>
        <div id="applicationtablecontainer">
            <div id="application_view" >
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    viewapplication(<?= $data['applicationcontrol'] ?>);

    function viewapplication( control, obj ) {
        var div = $( "#application_view" );
        div.html( "Please wait loading..." );

        $.post( "/?control=applications/main/view&ajax", {"control": control},
            function html( response ) {
                div.html( response );
            }
        );

    }
 </script>