<?php

/**
 * @author by wesleyhann
 * @date 2014/01/13
 * @time 4:58 PM
 */

?>

<h2>Update Product Listing</h2>
<br /><br />
<a class="nano-btn btn-primary" href="/assets/docs/pricelist.xlsx"><i class="fa fa-download"></i> Download Price List Template</a>
<br /><br />
<form class="nano-form" name="price-list-update" id="price-list-update" action="/?control=dashboard/main/pricelistupdate&ajax" method="post" enctype="application/x-www-form-urlencoded">

	<input type="file" name="price-list" id="price-list" />

	<input type="hidden" name="application-control" value="<?php echo $data['application']; ?>" />

</form>