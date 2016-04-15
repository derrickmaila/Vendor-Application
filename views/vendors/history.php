
<?php

/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

$usertypecontrol = $user->usertypecontrol;
$usercontrol = $user->control;

if ( !isset( $_GET[ 'withoutbody' ] ) ) { ?>
    <div id="system">
    <h1 class="title">Response History</h1>
    <br />
    <div id="action-buttons">
        <form class="nano-form grid-block">
            <input type="text" class="grid-box" placeholder="Search" id="search-keyword"/>
            <button id="search-submit" type="button" class="nano-btn grid-box"><i>Search</i></button>
            <button class="nano-btn" id="reset-search" type="reset"><i>Cancel</i></button>
        </form>
    </div>

    <div id="inboxtablecontainer">
<?php }

?>
    <div id="errorboxParent"></div>
    <table class="display-table">
        <thead>
        <tr>
            <th style="width: 20%;">REQ #.</th>
            <th style="width: 20%;">Subject</th>
            <th style="width: 25%;">Buyer</th>
            <th style="width: 10%;">Urgency</th>
            <th style="width: 15%;">Expiry Date</th>
            <th style="width: 10%;">Status</th>
            <th style="width:6%;"></th>
            <th style="width:6%;">Action(s)</th>
        </tr>

        </thead>
        <tbody>
            <?php
            //print_r($rfqs);
            if ( $rfqs ) {
                $count = 0;
                foreach ( $rfqs as $rfq ) {
                    if(empty($rfq->responses[0])){
                        continue;
                    }
                    if($rfq->RequisitionStatus == 4){
                        if($rfq->vendorcontrol == $usercontrol){
                            $cReqStatus = 4;
                        }else{
                            $cReqStatus = 3;
                        }

                        if($cReqStatus == 1){
                            $cReqStatus = 'New';
                        }else if($cReqStatus == 2){
                            $cReqStatus = 'Saved';
                        }else if($cReqStatus == 3){
                            $cReqStatus = '<em style="color: #38424b !important">Rejected</em>';
                            $cStatus = 'Rejected';
                        }else if($cReqStatus == 4){
                            $cReqStatus = '<em style="color: #00778f;">Approved</em>';
                            $cStatus = 'Approved';
                        }else if($cReqStatus == 5){
                            $cReqStatus = '<em style="color: #808080">Cancelled</em>';
                            $cStatus = 'Cancelled';
                        }else if($cReqStatus == 6){
                            $cReqStatus = 'Voided';
                        }

                    }else{
                        $cReqStatus = '<em  style="color: #808080">Pending...</em>';
                        $cStatus = 'Pending';

                        if($rfq->responses[0]->rfqheaderresponsestatus < 2){
                            continue;
                        }
                    }

                    $total = $rfq->rejected_count[0];

                    if($total->total != 0){
                        continue;
                    }

            ?>

                <tr class="dashboard" data-name="<?= strtolower($rfq->POPRequisitionNumber.' '.$rfq->RequisitionDescription.' '.$rfq->DomainUserName.' '.$rfq->REQDATE.' '.$cStatus.' '.$rfq->totalresponses.' '.$rfq->COMMNTID); ?>">
                    <td>
                        <i style="color: #00778f !important; font-style: italic; font-weight: bold;"><?= $rfq->POPRequisitionNumber; ?></i>
                    </td>
                    <td><?= $rfq->RequisitionDescription; ?></td>
                    <td><?= $rfq->DomainUserName; ?></td>
                    <td><?= $rfq->COMMNTID; ?></td>
                    <td><?= date_format(date_create($rfq->expirydate), "Y-m-d"); ?></td>
                    <td><?= $cReqStatus ?></td>
                    <td></td>
                    <td>
                        <button type="button" class="nano-btn"
                                onclick="viewRfq(<?= $rfq->control; ?>, <?= $rfq->responses[0]->control; ?>, <?= $usercontrol; ?>, this);"><i>View</i>
                        </button>
                    </td>
                </tr>
            <?php
                }

            } else {
            ?>
            <tr id="tr-hide">
                <td colspan="10">
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



    <script>

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


    $( document ).ready( function () {


//        $( '#search-submit' ).bind( 'click', function () {
        $('#search-keyword').keypress( function () {

            if($('#search-keyword').val() < ''){

                $( '#reset-search' ).bind( 'click', function () {
                    $( 'tr.dashboard' ).removeClass( 'hide' );
                    $( '.no-results' ).remove();
                } )

            }

            $( 'tr.dashboard' ).addClass( 'hide' )
            $( '.no-results' ).remove();

            // init vars
            var a = $( '#search-keyword' ).val().toLowerCase();


            if ( a.length != 0 ) {

                $( 'tr.dashboard' ).each( function () {

                    $( '.display-table .dashboard[data-name*=' + a.substring(1) + ']' ).removeClass( 'hide' );

                } )
                $( '.no-results' ).remove();

            }
            else {
                $( 'tr.dashboard' ).removeClass( 'hide' )
            }



            var resultCount = 0;

            $( '.dashboard:visible' ).each( function () {
                resultCount++;
            } )

            if ( resultCount == 0 ) {
                $( '.display-table' ).append( '<tr class="no-results"><td colspan="10">No results Found</td></tr>' );
            }


        } )

        $( '#reset-search' ).bind( 'click', function () {
            $( 'tr.dashboard' ).removeClass( 'hide' );
            $( '.no-results' ).remove();
        } )

    } );

    </script>

<?php } ?>