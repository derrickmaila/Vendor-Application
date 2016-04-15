

    <?php

    if(!empty($data) ){
        ?>
        <h2>Requisition Audit <i style="color: #00778f !important; font-style: italic; font-weight: bold;font-size: 15px;"><?= $data[0]->gpvendor; ?></i></h2>
        <?php
        foreach($data as $key => $audit){
            //print_r($spec);
    ?>
        <table class="form-table nano-form display-table" style="margin: 10px 0 10px 0;">
            <tr style="border-top: solid thin #00778f; border-bottom: none;">
                <td width="15%" style="color: #808080;"><?= $audit->name ?></td>
                <td width="30%" style="color: #808080;text-align: right;">Old Value</td>
                <td width="30%" style="color: #808080;text-align: right;">New Value</td>
                <td width="40%" style="color: #808080;text-align: right;">Audit Date|Time</td>
            </tr>
            <tr>
                <?php
                if(trim($audit->name) == 'submitted'){
                    if($audit->oldvalue == 1 or $audit->newvalue == 1){
                        $cStatus1 = 'New / Saved';
                    }

                    if($audit->oldvalue == 3 or $audit->newvalue == 3){
                        $cStatus2 = 'Submitted / Completed';
                    }
                }else{
                    $cStatus1 = $audit->oldvalue;
                    $cStatus2 = $audit->newvalue;
                }
                ?>
                <td></td>
                <td style="text-align: right;"><?= $cStatus1; ?></td>
                <td style="text-align: right;"><?= $cStatus2; ?></td>
                <td style="text-align: right;"><?= $audit->auditdate; ?></td>
            </tr>
        </table>
    <?php
        }
    ?>

    <?php
    }else{
    ?>
        <table class="form-table nano-form display-table">
            <tr>
                <td colspan="5">
                    No specifications found
                </td>
            </tr>
        </table>
    <?php
    }
    ?>
