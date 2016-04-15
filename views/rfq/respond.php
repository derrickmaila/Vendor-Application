<?php
/**
 *
 * @author  johank
 * @since   2015/06/26
 * @time    8:31 AM
 *
 */

$usertypecontrol =  $rfq->user->usertypecontrol;
//print_r( $rfq->user );

?>
<script>
    $('.date').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d',
        minDate:'-1970/01/02' // yesterday is minimum date
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

<!--TODO CHANGE TO prod when go-live -->
<h2>Line Response</h2>
<br />

        <?php
        //print_r($rfq->lines);
        foreach($rfq->lines as $line){
        $rfqheaderresponsecontrol = $line->rfqheaderresponsecontrol;

        $linedata[] = $line->lcontrol;
        $response = $line->lines_response[0];

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
                    <button type="button" class="nano-btn" onclick="viewItemSpec('<?= $line->ITEMNMBR; ?>', this);">
                        <i>View</i>
                    </button>
                </td>
                <td width="90" style="color:#00778f !important;text-align: right;">Description<br />( <?= $line->ITEMNMBR; ?> )</td>
                <td width="512"><?= $line->ITEMDESC; ?></td>
            </tr>
            <tr>
                <td></td>
                <td style="color:#00778f !important;text-align: right;">Price / <?= $line->UOFM; ?></td>
                <td>
                    <input <?= ($usertypecontrol == 8 ? 'disabled style="cursor: not-allowed"':''); ?> class="notEmpty" name="price" id="price" type="text" data="Unit Price,<?= $line->lcontrol; ?>,<?= $response->price; ?>,<?= $line->QTYORDER; ?>" <?= (!empty($response->price)? 'value="'.number_format((float)$response->price, 2, '.', '').'"':'placeholder="'.number_format((float)$response->price, 2, '.', '').'"'); ?>" onchange="validateForm(this);" onkeyup="calc_total<?= $line->LineNumber; ?>(this, <?= $line->QTYORDER; ?>);" />
                    <?= (empty($response->price) ? '<br /><em style="color: #983228">Please enter the Unit Price.</em>' : '') ?>
                </td>
            </tr>

            <tr>
                <td></td>
                <td style="color:#00778f !important;text-align: right;">Total</td>
                <td>
                    <input <?= ($usertypecontrol == 8 ? 'disabled style="cursor: not-allowed"':'disabled style="cursor: not-allowed"'); ?> class="notEmpty" name="total" id="total<?= $line->LineNumber; ?>" type="text" data="Total,<?= $line->lcontrol; ?>,<?= $response->price*$line->QTYORDER; ?>" value="<?= number_format((float)($response->price*$line->QTYORDER), 2, '.', ''); ?>" onchange="validateForm(this);" />
                </td>
            </tr>
            <script>

                function calc_total<?= $line->LineNumber; ?>(obj, qtyordr){
                    var value = $(obj).val();
                    var qty = qtyordr;

                    var total = (value*qty).toFixed(2);
                    $("#total<?= $line->LineNumber; ?>").val(total);

                }

            </script>
            <tr>
                <td></td>
                <?php
                $date = $response->deliverydate;
                if(!empty($date)){
                    $date = date_create($date);
                }else{
                    $date = '';
                }
                ?>
                <td style="color:#00778f !important;text-align: right;">Delivery Date</td>
                <td>
                    <input <?= (empty($date)? 'placeholder=""':''); ?> <?= ($usertypecontrol == 8 ? 'disabled style="cursor: not-allowed"':''); ?> name="deliverydate" id="deliverydate<?= $line->lcontrol; ?>" type="text" data="Delivery Date,<?= $line->lcontrol; ?>,<?= date_format($date, "Y-m-d"); ?>" value="<?= date_format($date, "Y-m-d"); ?>" readonly="true" class="<?= ($usertypecontrol == 8 ? '':'date'); ?> notEmpty" onblur="validateForm(this);" />
                    <?= (empty($date) ? '<br /><em style="color: #983228">Please change the date to the correct delivery date.</em>' : '') ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="color:#00778f !important;text-align: right;">Comments</td>
                <td>
                    <textarea <?= ($usertypecontrol == 8 ? 'disabled style="cursor: not-allowed"':''); ?> name="comments" id="comments" data="Comments,<?= $line->lcontrol; ?>, <?= $response->comments; ?>" onchange="validateForm(this);"><?= $response->comments; ?></textarea>
                </td>
            </tr>
        </table>
    <?php
        }
        if($usertypecontrol != 8 || $audit->count > 0){
    ?>
        <form id="responseForm" name="responseForm" action="" method="POST">
            <filedset>
                <button id="respondBtn" style="float: right" type="button" class="nano-btn" onclick="completeRfq('<?= $rfq->POPRequisitionNumber; ?>', <?= $rfq->control ?>, <?= (empty($response->rfqheaderresponsecontrol) ? '0':$response->rfqheaderresponsecontrol); ?>, '<?= implode("#", $linedata); ?>', 'submitted', '1', '3', this);"><i class="fa fa-thumbs-up"></i> Complete
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
            $('#respondBtn').hide();
        }else{
            $('#respondBtn').show();
        }
    }
    disable();

    // Form Validator -------------------------------------------------------------------------------
    function validateForm(obj){
        $("#errorbox").hide();

        var msg = '';
        var error = '';
        jQuery(obj).each(function() {
            var currentElement = $(this);

            var value = currentElement.val(); // if it is an input/select/textarea field
            var name = currentElement.attr( "name" );
            var dataName = currentElement.attr( "data").split(",");

            if(value > ''){
                var inputs = {"value":  value, "oldvalue": dataName[2], "rfqheadercontrol": <?= $rfq->control ?>, "rfqheaderresponsecontrol": '<?= (empty($rfqheaderresponsecontrol) ? $rfq->user->rfqheaderresponsecontrol:$rfqheaderresponsecontrol); ?>', "rfqlinecontrol": dataName[1], "requisitionnumber": '<?= $rfq->POPRequisitionNumber; ?>'};
                var json = { "data": inputs, "name": name };

                //console.log(inputs);
                $.post("/?control=dashboard/main/setRfqResponses&ajax", {"json": json },
                    function(data){
                        console.log(data);
                        if(data == 1){
                            error += '<div class="confirmation-box">'+ucfirst(name)+' saved successfully!!</div>';
                            $('#errorbox').html(error);
                            //alert('1');
                        }else if(data == 0){
                            error += '<div class="error-box">There was a problem while saving the '+ucfirst(name)+', please try again later!</div>';
                            $('#errorbox').html(error);
                            //alert('0');
                        }else{
                            error += '<div class="message-box">'+data+'</div>';
                            $('#errorbox').html(error);
                            //alert('else');
                        }

                    }
                );
            }else{
                msg += "Please complete field ' "+ucfirst(dataName[0])+" '\n";
            }
        });
        disable();
    }

    function completeRfq(reqnumber, rfqheadercontrol, rfqheaderresposnecontrol, rfqlinecontrol, name, oldvalue, newvalue, obj){
        $(obj).hide();

        var inputs = {"requisitionnumber": reqnumber, "rfqheadercontrol": rfqheadercontrol, "rfqheaderresponsecontrol": rfqheaderresposnecontrol ,"rfqlinecontrol": rfqlinecontrol, "name": name, "oldvalue": oldvalue, "value":  newvalue };
        var error  = '';
        //console.log(inputs);
       $.post("/?control=dashboard/main/completeRfqResponse&ajax", {"json": inputs },
            function(data){
                console.log(data);
                if(data == 1){
                    $('.ui-button').click();
                    error += '<br /><div class="confirmation-box">RFQ completed successfully!!</div>';
                    $('#errorboxParent').html(error);
                    //alert('1');
                }else if(data == 0){
                    $(obj).show();
                    error += '<br /><div class="error-box">There was a problem while saving the completion of the RFQ, please try again later!</div>';
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



        setTimeout(function() {
            window.location = '/?control=dashboard/main/inbox';
        }, 2500);
    }


    function ucfirst(str) {
        var firstLetter = str.substr(0, 1);
        return firstLetter.toUpperCase() + str.substr(1);
    }

</script>