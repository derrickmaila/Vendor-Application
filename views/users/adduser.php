<?php

    $usertypes = $data['user-types'];

?>
    <h2>Add User</h2>
<br/><br/>
    <form action="" class="form-group" method="post" enctype="application/x-www-form-urlencoded" name="userdata" id="adduser">
        <table>
            <tr>
                <td>
                    <label for="username">Username</label>
                <td>
                    <input type="text" name="username" id="username" autocomplete="off" value="">
                    <div class="response-message"></div>
                </td>

                </td>
            </tr>
            <tr>
                <td>
                    <label for="password">Password</label>
                <td>
                    <input type="password" name="password" id="password" autocomplete="off" value="">
                    <div class="response-message"></div>
                </td>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="usertypecontrol">User type</label>
                <td>
                    <select name="usertypecontrol" id="usertypecontrol">
                        <?php foreach($usertypes as $usertype) { ?>
                            <?php if($usertype->control == $user->usertypecontrol) { ?>
                                <option value="<?=$usertype->control?>" selected><?=$usertype->description?></option>
                            <?php } else { ?>
                                <option value="<?=$usertype->control?>"><?=$usertype->description?></option>
                            <?php } ?>
                            }
                        <?php } ?>
                    </select>
                </td>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contacttypecontrol">Contact type</label>
                <td>
                    <select name="isteamleader">
                        <option value="0">Normal User</option>
                        <option value="1">Team Leader</option>
                    </select>
                </td>
                </td>
            </tr>
            <!--<tr>
			<td>
				<label for="permissions">Permissions</label>
				<td>
					<input type="text" name="permissions" id="permissions" value="<?=$permissions?>">
					<div id="permissiontable"></div>
				</td>
			</td>
		</tr>-->
        </table>
    </form>

