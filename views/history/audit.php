<?php

/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

//print_r(
//    $user
//);
//$usertypecontrol = $user->usertypecontrol;
//$usercontrol = $user->control;

if ( !isset( $_GET[ 'withoutbody' ] ) ) { ?>
<div id="system">
<h1 class="title">History for <i style="color: #00778f !important; font-style: italic; font-weight: bold;font-size: 20px;"><?= $_POST['rfq'] ?></i>  (<?= count($data) ?>)</h1>
<div id="action-buttons">
    <form action="" class="nano-form grid-block">
        <input type="text" class="grid-box" placeholder="Search" id="search-keyword"/>
        <button id="search-submit" type="button" class="nano-btn grid-box"><i>Search</i></button>
        <div class="grid-box">
            <button class="nano-btn" id="reset-search" type="reset"><i>Cancel</i></button>
        </div>
    </form>
</div>

<div id="inboxtablecontainer">
<?php } ?>
    <div id="errorboxParent"></div>
    <table class="display-table">
        <thead>
        <tr>
            <th style="width: 15%;">Subject</th>
            <th style="width: 20%;">Vendor</th>
            <th style="width: 15%;">Response Date|Time</th>
            <th style="width: 15%;">Total Cost</th>
            <th style="width: 15%;">Buyer</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 6%;"></th>
            <th style="width: 6%;">Action(s)</th>
        </tr>

        </thead>
        <tbody>
            <?php
            //print_r($data);
            if ( $data ) {
                foreach ( $data as $responses ) {

                    if($usertypecontrol == 8){
                        $cReqStatus = $responses->RequisitionStatus;
                       // print $cReqStatus.'#';
                        if($cReqStatus != 2){
                            continue;
                        }
                    }



                    $cReqStatus = $responses->rfqheaderresponsestatus;

                    $buyername = '<em style="color: #808080">N/A</em>';
                    if($cReqStatus == 4){
                        $buyername = $responses->buyername;
                    }

                    if($cReqStatus == 1){
                        $cReqStatus = 'New';
                    }else if($cReqStatus == 2){
                        $cReqStatus = 'Saved';
                    }else if($cReqStatus == 3){
                        $cReqStatus = '<em style="color: #38424b !important">Rejected</em>';
                    }else if($cReqStatus == 4){
                        $cReqStatus = '<em style="color: #00778f;">Approved</em>';
                        $cStatus = 'Purchased';
                    }else if($cReqStatus == 5){
                        $cReqStatus = '<em style="color: #808080">Cancelled</em>';
                        $cStatus = 'Cancelled';
                    }else if($cReqStatus == 6){
                        $cReqStatus = 'Voided';
                    }


            ?>

            <tr class="inbox">
                <td><?= $responses->RequisitionDescription; ?></td>
                <td><?= $responses->vendname.' (<em style="color: #00778f;">'.$responses->gpvendor.'</em>)'; ?></td>
                <td><?= $responses->responsedate; ?></td>
                <td>R <?= number_format((float)$responses->total_price, 2, '.', ''); ?></td>
                <td><?= $buyername; ?></td>
                <td><?= $cReqStatus; ?></td>
                <td>
                    <button type="button" class="nano-btn"
                            onclick="viewRfq(<?= $responses->rfqheadercontrol; ?>, <?= $responses->rfqheaderresponsecontrol ?>, <?= $responses->vendorusercontrol; ?>, this);"><i>View</i>
                    </button>
                </td>
                <td>
                    <button type="button" class="nano-btn"
                            onclick="rfqAudit(<?= $responses->rfqheadercontrol; ?>, <?= $responses->rfqheaderresponsecontrol ?>, <?= $responses->vendorusercontrol; ?>, this);"><i>Audit</i>
                    </button>
                </td>
            </tr>
            <?php
                }

            } else {
            ?>
            <tr id="tr-hide">
                <td colspan="8">
                    No results found
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php if ( !isset( $_GET[ 'withoutbody' ] ) ) { ?>
</div>


    <script type="text/javascript">

    function viewRfq( control, rfqheaderresponsecontrol, usercontrol, obj ) {

        var div = $( "<div>" );
        div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );

        div.dialog();
        div.dialog( {
            "modal": "true",
            "width": "40%",
            dialogClass: 'whiteModalbg',
            position: ['center', 'top'],
            close: function () {
                div.remove();
            },
            buttons: {
                "Close": function () {
                    div.remove();
                }
            }
        } );

        $.post( "/?control=dashboard/main/callback&ajax", {"control": control, "usercontrol": usercontrol},
            function html( response ) {
                div.html( response );
            }
        );

    }


    function rfqAudit(  rfqheadercontrol, rfqheaderresponsecontrol, usercontrol, obj ) {

        var div = $( "<div>" );
        div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );

        div.dialog();
        div.dialog(
            {
                "modal": "true",
                "width": "35%",
                dialogClass: 'whiteModalbg',
                close: function () {
                    div.remove();
                },
                buttons: {
                    "Close": function () {
                        div.remove();
                    }
                }
            }
        );

        $.post( "/?control=dashboard/main/viewRfqAudit&ajax", {"control": rfqheadercontrol, "responsecontrol": rfqheaderresponsecontrol, "usercontrol": usercontrol},
            function html( response ) {
                div.html( response );
            }
        );
    }


    // Item details -------------------------------------------------------------------------------
    function viewItemSpec( itmnmbr, obj ) {

        var div = $( "<div>" );
        div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );

        div.dialog();
        div.dialog(
            {
                "modal": "true",
                "width": "35%",
                dialogClass: 'whiteModalbg',
                close: function () {
                    div.remove();
                },
                buttons: {
                    "Close": function () {
                        div.remove();
                    }
                }
            }
        );

        $.post( "/?control=dashboard/main/viewItemSpec&ajax", {"ITEMNMBR": itmnmbr},
            function html( response ) {
                div.html( response );
            }
        );
    }

    function searchinbox( keyword ) {
        $.post( "/?control=inbox/main/search&ajax&withoutbody", {"keyword": keyword},
            function html( response ) {
                if ( response.html ) {
                    $( "#inboxtablecontainer" ).html( response.html );
                } else {
                    alert( "An unexpected error occurred" );
                }
            }, 'json' );

    }

    $( document ).ready( function () {


        $("#search-keyword").keypress(function(event) {
            if ( event.which == 13 ) {
                event.preventDefault();
                $( "#search-submit" ).trigger( "click" );
            }
        });

        $( '#search-submit' ).bind( 'click', function () {

            $( 'tr.inbox' ).addClass( 'hide' );
            $( '.no-results' ).remove();

            // init vars
            var a = $( '#search-keyword' ).val().toLowerCase();

            if ( a.length != 0 ) {

                $( 'tr.inbox' ).each( function () {


                    $( '.display-table .inbox[data-name*=' + a + ']' ).removeClass( 'hide' );

                } )

            }
            else {
                $( 'tr.inbox' ).removeClass( 'hide' )
            }

            var resultCount = 0;

            $( '.inbox:visible' ).each( function () {
                resultCount++;
            } )

            if ( resultCount == 0 ) {
                searchkeyword = document.getElementById("search-keyword").value;
                $( '.display-table' ).append( '<tr class="no-results"><td colspan="10">No results found with keyword "'+a+'" </td></tr>' );
                $('#tr-hide').hide();
            }


        } )

        $( '#reset-search' ).bind( 'click', function () {
            $( 'tr.inbox' ).removeClass( 'hide' );
            $( '.no-results' ).remove();
        } )

    } );

    </script>

<?php } ?>