				<form action="/?control=applications/main/uploadconfirmation&ajax" method="post" enctype="multipart/form-data" target="upload-iframe" id="upload-form">
				<input type="hidden" name="control" value="<?php echo $_POST['control']; ?>">
					<input type="hidden" name="stage" value="<?php echo $_POST['stage']; ?>">
					
				</form>
				<h1>Would you like to approve this application?</h1>
<!--
	<table class="display-table">
		<tr>
			<td width="100%">
				<h1 class="title" style="font-size: 12px; margin: 0;">Upload a confirmation document to proceeed</h1>
			</td>
		</tr>
		<tr>
			<td>
				<button onclick="addmore();" value="Add another upload box" class="nano-btn">
					Add Additional File
				</button>
			</td>
		</tr>
		<tr>
			<td>
				<form action="/?control=applications/main/uploadconfirmation&ajax" method="post" enctype="multipart/form-data" target="upload-iframe" id="upload-form">
					<span id="uploads">
						<div class="upload-box">
							<input type="file" name="uploads[]" class="confirmation-file" />
							<a href="#" onclick="removeupload(this);">X</a>
						</div>
					</span>
					<input type="hidden" name="control" value="<?php echo $_POST['control']; ?>">
					<input type="hidden" name="stage" value="<?php echo $_POST['stage']; ?>">
					<input type="button" class="nano-btn" onclick="verify();" value="Upload" />
				</form>

				<iframe src="/?control=applications/main/uploadeddocs&ajax" name="upload-iframe" id="upload-frame" width="100%"></iframe>
			</td>
		</tr>
	</table>
	
	<div class="ajax"></div>

<table>
	<tr>
		<td colspan="2"><h1>Downloads</h1></td>
	</tr>

	<tr>
		<td colspan="2">
			<table class="display-table">

				<tr>
					<th>Filename</th>
					<th>Date</th>
					<th>Type</th>
					<th>Size</th>
					
					<th>Download</th>
				</tr>

				<?php if ( !empty( $conf_files ) ) : ?>

					<?php foreach ( $conf_files as $file ) :
						$ext = substr($file->filename, strpos($file->filename, "."));
					 ?>
		 
						<tr>

							<td><?php echo $file->filename; ?></td>
							<td><?php echo $file->datelogged; ?></td>
							<td><?php echo $file->mimetype; ?></td>
							<td><?php echo( $file->size / 1000 ); ?>kb</td>
							
							<td>
								<a href="/?control=applications/main/download&file=<?= $file->filepath ?><?= $ext?>&filename=<?= $file->filename ?>&filetype=<?php echo $file->mimetype; ?>&controlsource=<?php echo $application->control; ?>&ajax&confirmation=1">Download</a>
							</td>

						</tr>

					<?php endforeach;
				else:      ?>

					<tr>

						<td colspan="5">No files found</td>

					</tr>

				<?php endif; ?>

			</table>
		</td>
	</tr>
</table>
<?php if($application->applicationstagecontrol != 5) { ?>
<table>
	<tr>
		<td colspan="2"><h1>Downloads - Phase 2</h1></td>
	</tr>

	<tr>
		<td colspan="2">
			<table class="display-table">

				<tr>
					<th>Filename</th>
					<th>Date</th>
					<th>Type</th>
					<th>Size</th>
					
					<th>Download</th>
				</tr>

				<?php if ( !empty( $phase2 ) ) : ?>

					<?php foreach ( $phase2 as $file ) :
						$ext = substr($file->filename, strpos($file->filename, "."));
					 ?>
		 
						<tr>

							<td><?php echo $file->filename; ?></td>
							<td><?php echo $file->datelogged; ?></td>
							<td><?php echo $file->mimetype; ?></td>
							<td><?php echo( $file->size / 1000 ); ?>kb</td>
							
							<td>
								<a href="/?control=applications/main/download&file=<?= $file->filepath ?><?= $ext?>&filename=<?= $file->filename ?>&filetype=<?php echo $file->mimetype; ?>&controlsource=<?php echo $application->control; ?>&ajax&confirmation=1">Download</a>
							</td>

						</tr>

					<?php endforeach;
				else:      ?>

					<tr>

						<td colspan="5">No files found</td>

					</tr>

				<?php endif; ?>

			</table>
		</td>
	</tr>
</table>
<?php } ?>

<script type="text/javascript">

	$( document ).ready( function () {

		var fh = $( '#upload-form' ).height();
			fw = $( '#upload-form' ).width();

		$('.ajax' ).width( fw ).height( fh );

		$('#confirmation-file' ).change( function() {

			$('#upload-form' ).ajaxSubmit();

			return false;

		})

	} );

	function addmore() {
		$("#uploads").append('<div class="upload-box"><input type="file" name="uploads[]" class="confirmation-file" /><a href="#" onclick="removeupload(this);">X</a></div>');
	}
	function removeupload(obj) {
		$(obj).parent().remove();
	}
	function verify() {
		var files = false;
		$('input[type="file"]').each(function() {
			if($(this).val()!="") {
				files = true;
			}
		});
		if(files) {
			$("#upload-form").submit();
		} else {
			alert("You need to select a file to upload.");
			return;
		}
	}
</script>
-->