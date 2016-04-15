
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
<h1 class="title">Requisition History (<?= count($data); ?>)</h1>
<br />
<div id="action-buttons">
    <form class="nano-form grid-block" action="/?control=history/main/index">
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
            <th style="width: 13%;">REQ #.</th>
            <th style="width: 20%;">Subject</th>
            <th style="width: 18%;">Buyer</th>
            <th style="width: 23%;">Vendor</th>
            <th style="width: 18%;">REQ Date</th>
            <th style="width: 18%;">Status</th>
            <th style="width: 10%;"></th>
            <th style="width: 10%;">Action(s)</th>
        </tr>

        </thead>
        <tbody>
        <?php
        //print_r($data);
        if ( $data ) {

            foreach ( $data as $rfq ) {
//                print_r( $rfq->response[0]->control );

                $cReqStatus = $rfq->RequisitionStatus;
                if($cReqStatus == 1){
                    $cReqStatus = 'Saved';
                }else if($cReqStatus == 2){
                    $cReqStatus = 'Submitted';
                }else if($cReqStatus == 3){
                    $cReqStatus = 'Partially Purchased';
                }else if($cReqStatus == 4){
                    $cReqStatus = '<em style="color: #00778f;">Approved</em>';
                    $cStatus = 'Purchased';
                }else if($cReqStatus == 5){
                    $cReqStatus = '<em style="color: #808080">Cancelled</em>';
                    $cStatus = 'Cancelled';
                }else if($cReqStatus == 6){
                    $cReqStatus = 'Voided';
                }

                $vendornameid = '<em style="color: #808080">N/A</em>';
                if(!empty($rfq->vendname)){
                    $vendornameid = $rfq->vendname.' (<em style="color: #00778f;">'.$rfq->gpvendor.'</em>)';
                }

                ?>

                <tr class="dashboard" data-name="<?= strtolower($rfq->POPRequisitionNumber.' '.$cStatus.' '.$rfq->DomainUserName.' '.$rfq->REQDATE.' '.$rfq->RequisitionDescription); ?>">
                    <td>
                        <i style="color: #00778f !important; font-style: italic; font-weight: bold;"><?= $rfq->POPRequisitionNumber; ?></i>
                    </td>
                    <td><?= $rfq->RequisitionDescription; ?></td>
                    <!-- TODO ### -->
                    <td><?= $rfq->DomainUserName; ?></td>
                    <td><?=  $vendornameid; ?></td>
                    <td><?= date_format(date_create($rfq->REQDATE), "Y-m-d"); ?></td>
                    <td><?= $cReqStatus ?></td>
                    <td>
                    <button type="button" class="nano-btn"
                            onclick="viewRfq(<?= $rfq->rfqheadercontrol; ?>, <?= (empty($rfq->response[0]->control)? '0':$rfq->response[0]->control); ?>, <?= $rfq->vendorcontrol; ?>, this, <?= $rfq->RequisitionStatus; ?>);"><i>View</i>
                    </button>
                    </td>
                    <td>
                        <form method="post" action="/?control=history/main/audit">
                            <input name="rfq" value="<?php print $rfq->POPRequisitionNumber; ?>" hidden="" style="display: none;" />
                            <button <?= (empty($rfq->response[0]->control) ? 'hidden style="display:none;"':''); ?> type="button" class="nano-btn" onclick="this.form.submit()"><i>Responses</i>
                            </button>
                        </form>
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
//jQuery/JS HERE

function viewRfq(  control, rfqheaderresponsecontrol, usercontrol, obj, status  ) {

    var div = $( "<div>" );
    div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );

    div.dialog();
    if(status > 4){
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
                "View Quotation": function () {
                    div.remove();
                    viewQuote( control, rfqheaderresponsecontrol, usercontrol, obj );

                }
            }
        } );
    }


    $.post( "/?control=dashboard/main/view&ajax", {"control": control, "usercontrol": usercontrol},
        function html( response ) {
            div.html( response );
        }
    );

}

function viewQuote( control, rfqheaderresponsecontrol, usercontrol, obj ) {

    var div = $( "<div>" ).insertBefore(  $("#footer-container") );
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