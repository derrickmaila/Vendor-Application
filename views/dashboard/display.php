
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
    <h1 class="title"><?= $usertypecontrol == 2 ? 'Quotation Requests' : 'Quotation Responses' ?></h1>
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
            <th style="width: 15%;">REQ #.</th>
            <th style="width: 20%;">Subject</th>
            <th style="width: 20%;">Buyer</th>
            <th style="width: 10%;">Urgency</th>
            <th style="width: 15%;">Expiry Date</th>
            <th style="width: 10%;">Status</th>
            <?php
            if($usertypecontrol == 8){
            ?>
            <th style="width: 10%;">Responses</th>
            <th style="width:8%;"></th>
            <?php
            }else{
            ?>
            <th style="width:8%;"></th>
            <?php
            }
            ?>
            <th style="width:8%;">Action(s)</th>
        </tr>

        </thead>
        <tbody>
            <?php
            //print_r($rfqs);
            if ( $rfqs ) {
                $count = 0;
                foreach ( $rfqs as $rfq ) {

                    if($usertypecontrol == 8){

                        if($cReqStatus == 6){
                            continue;
                        }

                        $cReqStatus = $rfq->RequisitionStatus;
                        if($cReqStatus == 2){
                            if($rfq->totalresponses == 0){
                                $cReqStatus = '<em style="color:#808080">Pending Responses</em>';
                                $cStatus = 'Pending Responses';
                            }else{
                                $cReqStatus = '<em style="color: #00778f">Pending Approval</em>';
                                $cStatus = 'Pending Approval';
                            }

                        }

                    }else{

                        if(empty($rfq->responses)){
                            $cReqStatus = '<em style="color: #38424b">New</em>';
                            $cStatus = 'New';

                            $cExpiryDate = date_format(date_create($rfq->expirydate), "Y-m-d");
                            $cExpiryDate = strtotime($cExpiryDate);
                            $cDateToday = strtotime(date("Y-m-d"));

                            if($cExpiryDate < $cDateToday){
                                $cReqStatus = '<em style="color: #983228">Expired</em>';
                            }
                        }else{



                            $cReqStatus = '<em style="color:#00778f">Saved</em>';
                            $cStatus = 'Saved';

                            $cExpiryDate = date_format(date_create($rfq->expirydate), "Y-m-d");
                            $cExpiryDate = strtotime($cExpiryDate);
                            $cDateToday = strtotime(date("Y-m-d"));

                            if($cExpiryDate < $cDateToday){
                                $cReqStatus .= ' (<em style="color: #983228">Expired</em>)';
                            }

                            if($rfq->responses[0]->rfqheaderresponsestatus > 2){
                                continue;
                            }
                        }
                        $total = $rfq->rejected_count[0];

                        if($total->total != 0){
                            continue;
                        }

                    }
            ?>

                <tr class="dashboard" data-name="<?= strtolower($rfq->POPRequisitionNumber.' '.$rfq->RequisitionDescription.' '.$rfq->DomainUserName.' '.$rfq->REQDATE.' '.$cStatus.' '.$rfq->totalresponses.' '.$rfq->COMMNTID); ?>">
                    <td>
                        <i style="color: #00778f !important; font-style: italic; font-weight: bold;"><?= $rfq->POPRequisitionNumber; ?></i>
                    </td>
                    <td><?= $rfq->RequisitionDescription; ?></td>
                    <!-- TODO ### -->
                    <td><?= $rfq->DomainUserName; ?></td>
                    <td><?= $rfq->COMMNTID; ?></td>
                    <td><?= date_format(date_create($rfq->expirydate), "Y-m-d"); ?></td>
                    <td><?= $cReqStatus ?></td>
                    <?php
                    if($usertypecontrol != 8){
                    ?>
                        <td>
                            <button type="button" class="nano-btn" onclick="viewRfq(<?= $rfq->control; ?>,  <?= $usercontrol; ?>, 2, 0, 'null');"><i>View</i>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="nano-btn" onclick="rejectRfq(<?= $rfq->control; ?>, <?= $usercontrol; ?>);"><i>Reject</i>
                            </button>
                        </td>
                    <?php
                    }
                    ?>

                    <?php
                    if($usertypecontrol == 8){
                    ?>
                    <td><?= $rfq->totalresponses; ?></td>
                    <td>
                        <form id="<?= trim($rfq->POPRequisitionNumber); ?>" method="post" action="/?control=dashboard/main/responses">
                            <input name="rfq" value="<?php print $rfq->POPRequisitionNumber; ?>" hidden="" style="display: none;" />
                            <button hidden="" style="display: none;"  type="button" class="nano-btn" onclick="this.form.submit()"><i>View</i>
                            </button>
                        </form>
                        <button type="button" class="nano-btn" onclick="viewRfq(<?= $rfq->control; ?>,  <?= $usercontrol; ?>, 8, <?= $rfq->totalresponses; ?>, '<?= trim($rfq->POPRequisitionNumber); ?>');"><i>View</i>
                        </button>
                    </td>
                    <td>
                        <button type="button" class="nano-btn"
                                onclick="cancelRfq(<?= $rfq->control; ?>, '<?= $rfq->POPRequisitionNumber; ?>');">
                            <i>Cancel</i>
                        </button>
                    </td>
                    <?php
                    }
                    ?>
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

        function viewRfq( control, usercontrol, usertypecontrol, responses, requisition ) {

            var div = $( "<div>" );
            div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );

            div.dialog();
            if(usertypecontrol == 2){

                div.dialog( {
                    "modal": "true",
                    "width": "40%",
                    dialogClass: 'whiteModalbg',
                    position: ['center', 'top'],
                    close: function () {
                        div.remove();
                    },
                    buttons: {
                        "Reject": function () {
                            div.remove();
                            rejectRfq( control, usercontrol );

                        },
                        "Respond": function () {
                            div.remove();
                            respondRfq( control, usercontrol );

                        }
                    }
                } );

            }else{
                if(responses == 0){

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

                }else{

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

                            },
                            "View Responses": function () {
                                div.remove();
                                $("#"+requisition).submit();

                            }
                        }
                    } );

                }
            }


            $.post( "/?control=dashboard/main/view&ajax", {"control": control, "usercontrol": usercontrol},
                function html( response ) {
                    div.html( response );
                }
            );

        }



        function respondRfq( control, usercontrol ) {

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
                    "Save & Close": function () {
                        div.remove();
                        window.location = '/?control=dashboard/main/inbox';
                    }
                }
            } );

            $.post( "/?control=dashboard/main/callback&ajax&req=1", {"control": control, "usercontrol": usercontrol},
                function html( response ) {
                    div.html( response );
                }
            );

        }

        function cancelRfq( control, requisition ) {

            var div = $( "<div>" );
            div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );

            div.dialog();

            div.html( "<h2>Are you sure you want to Cancel Requisition ' <i style='color: #00778f !important; font-style: italic; font-weight: bold; font-size: 18px;'>"+requisition+"</i> ' </h2>" );
            div.dialog( {
                "modal": "true",
                "width": "40%",
                dialogClass: 'whiteModalbg',
                close: function () {
                    div.remove();
                },
                buttons: {
                    "Yes": function () {
                        div.remove();
                        $.post( "/?control=dashboard/main/cancelRfq&ajax", {"control": control, "requisition": requisition},
                            function html( data ) {
                                var error = '';
                                console.log(data);
                                if(data == 1){
                                    error += '<br /><div class="confirmation-box">RFQ completed successfully!!</div>';
                                    $('#errorboxParent').html(error);
                                    //alert('1');
                                    div.remove();
                                    setTimeout(function() {
                                        window.location = '/?control=dashboard/main/inbox';
                                    }, 2000);
                                }else if(data == 0){
                                    error += '<br /><div class="error-box">There was a problem while saving the completion of the RFQ, please try again later!</div>';
                                    $('#errorboxParent').html(error);
                                    //alert('0');
                                    div.remove();
                                }else{
                                    error += '<br /><div class="message-box">'+data+'</div>';
                                    $('#errorboxParent').html(error);
                                    //alert('else');
                                }

                                $("#errorboxParent").html("")
                            }
                        );
                    },
                    "No": function () {
                        div.remove();
                    }
                }
            });
        }

        function rejectRfq( control, usercontrol ) {

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

            $.post( "/?control=dashboard/main/callbackRejection&ajax", {"control": control, "usercontrol": usercontrol},
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


            $( '#search-submit' ).bind( 'click', function () {

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