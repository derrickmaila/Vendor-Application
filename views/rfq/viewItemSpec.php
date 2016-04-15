<h2>Specifications</h2>

    <?php

    if(!empty($data) ){
        foreach($data as $key => $spec){
            //print_r($spec);
            if($key == 0){
    ?>
        <table class="form-table nano-form display-table" style="margin: 10px 0 10px 0;">
            <tr style="border-top: solid thin #00778f; border-bottom: none;">
                <td width="155" style="color: #808080">Item Number <i style="color:#00778f !important; font-style: italic;"><?= $spec->ITEMNMBR; ?></i></td>
                <td width="90" style="color:#00778f !important;text-align: right;"></td>
                <td width="512"><?= $spec->QTYORDER; ?></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td width="155"></td>
                <td width="90" style="color:#00778f !important;text-align: right;"><?= $spec->QAMEASSTRLHV; ?></td>
                <td width="512"><?= $spec->QATESTCONCI; ?></td>
            </tr>
    <?php
        }
    ?>
        </table>
    <?php
    }else{
    ?>
        <table class="form-table nano-form display-table">
            <tr>
                <td colspan="3">
                    No specifications found
                </td>
            </tr>
        </table>
    <?php
    }
    ?>
