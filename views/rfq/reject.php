<?php
/**
 *
 * @author  Sipho Mkhwanazi
 * @since   2015/07/09
 * @time    21:03 PM
 *
 */

$usertypecontrol =  $rfq->user->usertypecontrol;
//print_r($rfq);
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
<div id="errorboxParent"></div>
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
        <td width="155" style="color: #808080;">Buyer Details</td>
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
<table class="form-table nano-form display-table">
                <tr>
                    <td width="155"></td>
                    <td width="90" style="color:#00778f !important;text-align: right;width: 130px;">Reason</td>
                    <td width="512">
                        <select <?= ($usertypecontrol == 8 ? 'disabled':''); ?> class="notEmpty" type="text" name="action" id="action" onchange="disable();">
                            <option value="">Please select one</option>
                            <option value="Item Specification" <?php if ( $line->action == 1 ) { echo "selected"; } ?>>Item Specification</option>
                            <option value="Quantity" <?php if ( $line->action == 2 ) { echo "selected"; } ?>>Quantity</option>
                            <option value="No Stock" <?php if ( $line->action == 3 ) { echo "selected"; } ?>>No Stock</option>
                            <option value="Other" <?php if ( $line->action == 4 ) { echo "selected"; } ?>>Other</option>
                        </select>
                    </td>
                </tr>
    <tr>
        <td></td>
        <td style="color:#00778f !important;text-align: right;">Comments</td>
        <td>
            <textarea <?= ($usertypecontrol == 8 ? 'disabled':''); ?> class="notEmpty" name="comments" id="comments" onkeyup="disable();"></textarea>
        </td>
    </tr>
</table>
<?php
if($usertypecontrol != 8 || $audit->count > 0){

    ?>
    <form id="responseForm" name="responseForm" action="" method="POST">
        <filedset>
            <button id="rejectionBtn" style="float: right" type="button" class="nano-btn" onclick="completeRfq('<?= $rfq->POPRequisitionNumber; ?>', '<?= $rfq->control ?>' ,0 , 'all' ,'Rejected_Comments', 'false', '', this);">
                <i>Reject</i>
            </button>
        </filedset>
    </form>
<?php
}
?>

<script>

    function disable(){
        var disable;
        var value

        jQuery(".notEmpty").each(function() {
            var currentElement = $(this);

            value = currentElement.val(); // if it is an input/select/textarea field
            if(value <= ''){
                disable = 1;
            }


        });

        //console.log('Value = '+value);
        if(disable == 1){
            $('#rejectionBtn').hide();
        }else{
            $('#rejectionBtn').show();
        }
    }
    disable();

    function completeRfq(reqnumber, rfqheadercontrol, rfqheaderresposnecontrol, rfqlinecontrol, name, oldvalue, newvalue, obj){
        $(obj).hide();

        var comment = $("#action").val()+': '+$("#comments").val();
        var inputs = {"requisitionnumber": reqnumber, "rfqheadercontrol": rfqheadercontrol, "rfqheaderresponsecontrol": rfqheaderresposnecontrol
            ,"rfqlinecontrol": rfqlinecontrol, "name": name, "oldvalue": oldvalue, "value":  comment };
        var error  = '';
        //console.log(inputs);
        $.post("/?control=dashboard/main/completeRfqResponseRejection&ajax", {"json": inputs },
            function(data){
                console.log(data);
                if(data == 1){
                    $('.ui-button').click();
                    error += '<br /><div class="confirmation-box">RFQ rejected successfully!!</div>';
                    $('#errorboxParent').html(error);
                    // alert('1');

                }else if(data == 0){
                    $(obj).show();
                    error += '<br /><div class="error-box">There was a problem rejecting the RFQ, please try again later!</div>';
                    $('#errorbox').html(error);
                    //alert('0');

                }else{
                    $(obj).show();
                    error += '<br /><div class="message-box">'+data+'</div>';
                    $('#errorbox').html(error);
                    //alert('else');
                }

                $("#errorboxParent").show();
            }
        );
//        setTimeout(function() {
//            window.location = '/?control=dashboard/main/inbox';
//        }, 2500);
    }

    function rejectRFQ(reqnumber, rfqheadercontrol, rfqheaderresposnecontrol, rfqlinecontrol, name, oldvalue, newvalue ) {

        var div = $( "<div>" );
        div.html( '<i style="color: #00778f !important; font-style: italic;">Please wait loading...</i>' );

        div.dialog();
        div.dialog(
            {
                "modal": "true",
                "width": "40%",
                dialogClass: 'whiteModalbg',
                close: function () {
                    div.remove();
                },
                buttons: {
                    "Close": function () {
                        div.remove();
                    },
                    "Reject": function () {
                        div.remove();
                    }
                }
            }
        );

        $.post( "/?control=dashboard/main/completeRfqResponseRejection&ajax", {"requisitionnumber": reqnumber, "rfqheadercontrol": rfqheadercontrol, "rfqheaderresponsecontrol": rfqheaderresposnecontrol ,"rfqlinecontrol": rfqlinecontrol, "name": name, "oldvalue": oldvalue, "value":  newvalue },
            function html( response ) {
                div.html( response );
            }
        );
    }
</script>