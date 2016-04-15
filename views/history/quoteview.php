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
//print_r($rfq);
?>
<div id="errorbox"></div>
<table  class="display-table">
    <h2>Viewing Quotation for <i style="color:#00778f !important; font-style: italic;"><?= $rfq->POPRequisitionNumber; ?></i></h2>
    <br />
    <tr style="border-top: solid thin #00778f">
        <td width="128" >
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-user"></i>
            FROM</td>
        <?php
        $cBranchCode = explode("-",$rfq->lines[0]->LOCNCODE);
        $cBranchCode = str_replace("S", "PF",$cBranchCode[0]);
        ?>
        <td width="512" ><?= $rfq->vendname; ?></td>
        <td width="117"></td>
    </tr>
    <tr>
        <td>
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-calendar"></i>
            REQ DATE
        </td>
        <td><?= date_format(date_create($rfq->REQDATE), "Y-m-d"); ?></td>
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
        <td width="128">
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-calendar"></i>
            QUOTE DATE
        </td>
        <td width="512"><?= $rfq->responsedate; ?></td>
        <td width="117"></td>
    </tr>
    <tr>
        <td>
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-bookmark"></i>
            QUOTE STATUS
        </td>
        <td><?= ($rfq->vendorcontrol==$rfq->vendorusercontrol? '<em style="color: #00778f;">APPROVED</em>':($rfq->RequisitionStatus < 4? '<em style="color: #808080">PENDING...</em>':'<em style="color: #38424b !important">REJECTED</em>')); ?></td>
        <td></td>
    </tr>
    <tr>
        <td>
            <i style="color: #00778f !important;padding-right: 5px;" class="fa fa-calendar"></i>
            APPROVAL DATE
        </td>
        <td><?= ($rfq->vendorcontrol==$rfq->vendorusercontrol? $rfq->statusdate:'<em style="color: #38424b !important">N/A</em>'); ?></td>
        <td></td>
    </tr>
</table>
<?php if($rfq->vendorcontrol==$rfq->vendorusercontrol){ ?>
<table  class="display-table">
    <tr style="border-top: solid thin #00778f">
        <td width="155" style="color: #808080">Approver Details</td>
        <td width="90" style="color:#00778f !important;">Approver</td>
        <td width="512"><?= $rfq->buyername; ?></td>
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
<?php } ?>

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
                    <input disabled style="cursor: not-allowed" class="notEmpty" name="price" id="price" type="text" data="Unit Price,<?= $line->lcontrol; ?>,<?= $response->price; ?>,<?= $line->QTYORDER; ?>" value="<?= number_format((float)$response->price, 2, '.', ''); ?>" onchange="validateForm(this);" onkeyup="calc_total<?= $line->LineNumber; ?>(this, <?= $line->QTYORDER; ?>);" />
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
                }
                ?>
                <td style="color:#00778f !important;text-align: right;">Delivery Date</td>
                <td>
                    <input disabled style="cursor: not-allowed" name="deliverydate" id="deliverydate<?= $line->lcontrol; ?>" type="text" data="Delivery Date,<?= $line->lcontrol; ?>,<?= date_format($date, "Y-m-d"); ?>" value="<?= date_format($date, "Y-m-d"); ?>" readonly="true" class="<?= ($usertypecontrol == 8 ? '':'date'); ?> notEmpty" onblur="validateForm(this);" />
                    <?= (empty($date) ? '<br /><em style="color: #983228">Please change the date to the correct delivery date.</em>' : '') ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="color:#00778f !important;text-align: right;">Comments</td>
                <td>
                    <textarea disabled style="cursor: not-allowed" name="comments" id="comments" data="Comments,<?= $line->lcontrol; ?>, <?= $response->comments; ?>" onchange="validateForm(this);"><?= $response->comments; ?></textarea>
                </td>
            </tr>
        </table>
    <?php
        }
    ?>