<div id="form-reset">
    <h2>Password Reset</h2>
    <p style="color: #38424b">Please enter your email address below to reset your password.</p>
    <form id="reset-password" action="/?control=users/password/reset&task=reset" enctype="application/x-www-form-urlencoded" method="post" class="inline-form nano-form" role="form">
        <div class="form-group">
            <label for="emailreset">Email Address:</label>
            <input type="email" name="emailreset" id="emailreset" placeholder="example@example.com" />
        </div>
    </form>

    <div id="reset-error"></div>
</div>

<div id="reset-ajax"></div>

<script type="text/javascript">

    $(document).ready(function(){

        setInterval(function(){
            var email   = $('input#emailreset').val();
            var pattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(email.length > 4){
                if(pattern.test(email)){
                    $('input#emailreset').removeClass('invalid').addClass('valid');
                    $('#reset-error').html('<i class="fa fa-check valid"></i><span>Email looks good</span><div class="clearfix">');
                } else {
                    $(this).removeClass('valid').addClass('invalid');
                    $('#reset-error').html('<i class="fa fa-times invalid"></i><span>Invalid email address</span><div class="clearfix">');
                }
            }
        } , 500)

    });

</script>
