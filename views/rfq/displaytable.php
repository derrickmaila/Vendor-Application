<?php
/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

$usertypecontrol =  $rfq->user->usertypecontrol;
//print_r($data);
?>
<script>
    $('.date').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d'
        //minDate:'-1970/01/02', // yesterday is minimum date
        //maxDate:'+1970/01/02' // and tomorrow is maximum date calendar
    });

</script>
<div id="errorbox"></div>
<table  class="display-table">
    <h2>Viewing REQ: <i style="color:#00778f !important; font-style: italic;"><?= $rfq->POPRequisitionNumber; ?></i></h2>
    <br />
    <tr style="border-top: solid thin #00778f">
        <td width="128" >
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-user"></i>
            FROM</td>
        <?php
            $cBranchCode = explode("-",$rfq->lines[0]->LOCNCODE);
            $cBranchCode = str_replace("S", "PF",$cBranchCode[0]);
        ?>
        <td width="512" >Premier Foods, <?= $cBranchCode ?></td>
        <td width="117"></td>
    </tr>
    <tr>
        <td>
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-calendar"></i>
            RFQ DATE
        </td>
        <td><?= $rfq->REQDATE; ?></td>
        <td></td>
    </tr>
    <tr>
        <td>
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-bookmark"></i>
            SUBJECT
        </td>
        <td><?= $rfq->RequisitionDescription; ?></td>
        <td></td>
    </tr>
</table>

<table  class="display-table">
    <tr style="border-top: solid thin #00778f">
        <td width="155" style="color: #808080">Buyer Details</td>
        <td width="90" style="color:#00778f !important;">Buyer</td>
        <td width="512"><?= $rfq->DomainUserName; ?></td>
    </tr>
    <?php if(!empty($rfq->CONTACT)){ ?>
    <tr>
        <td></td>
        <td style="color:#00778f !important;">Contact</td>
        <td><?= $rfq->CONTACT; ?></td>
    </tr>
    <tr>
        <td></td>
        <td style="color:#00778f !important;">Number(s)</td>
        <td><?= $rfq->PHONE1.'<br />'.$rfq->PHONE2.'<br />'.$rfq->PHONE3; ?></td>
    </tr>
    <?php } ?>
</table>

<h2>REQ Lines</h2>
<br />

<?php

foreach($rfq->lines as $line){
    $rfqheaderresponsecontrol = $line->rfqheaderresponsecontrol;
    ?>
    <table class="form-table nano-form display-table">
        <tr style="border-top: solid thin #00778f">
            <td width="155" style="color: #808080">Line <i style="color:#00778f !important; font-style: italic;"><?= $line->LineNumber; ?></i></td>
            <td width="90" style="color:#00778f !important;text-align: right;">Quantity</td>
            <td width="512"><?= $line->QTYORDER; ?></td>
        </tr>
        <tr style="border-top: solid thin #00778f">
            <td></td>
            <td style="color:#00778f !important;text-align: right;">Unit</td>
            <td ><?= $line->UOFM; ?></td>
        </tr>
        <tr>
            <td>
                <button type="button" class="nano-btn" onclick="viewItemSpec('<?= trim($line->ITEMNMBR); ?>', this);">
                    <i>View</i>
                </button>
            </td>
            <td width="90" style="color:#00778f !important;text-align: right;">Description<br />( <?= $line->ITEMNMBR; ?> )</td>
            <td width="512"><?= $line->ITEMDESC; ?></td>
        </tr>
    </table>
<?php } ?>

<script>
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
</script>