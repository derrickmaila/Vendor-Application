<div id="system">

    <?php if($data == true):  ?>

        <h1 class="title">Successfully Updated!</h1>

    <?php else: ?>

		<h1 class="title">Update your Password</h1>

        <form class="inline-form nano-form form-vertical" method="post" enctype="application/x-www-form-urlencoded" action="/?<?php echo $_SERVER['QUERY_STRING']; ?>">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="repeat">Repeat Password</label>
                <input type="password" name="repeat" id="repeat">
            </div>
            <button style="margin-top: 15px" class="nano-btn" type="submit">Update</button>
        </form>

    <?php endif; ?>

</div>