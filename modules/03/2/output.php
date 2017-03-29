<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$pics_cols = 4;
	if($cols >= 10) {
		$pics_cols = 2;
	}
	else if ($cols <= 6) {
		$pics_cols = 6;
	}
	$type_thumb = "REX_VALUE[1]";
	$type_detail = "REX_VALUE[2]";
	$pics = preg_grep('/^\s*$/s', explode(",", REX_MEDIALIST[1]), PREG_GREP_INVERT)
?>
<div class="col-sm-12 col-md-<?php echo $cols; ?>">
	<div class="row">
		<?php
			foreach($pics as $pic) {
				$media = rex_media::get($pic);
				print '<a href="index.php?rex_media_type='. $type_detail .'&rex_media_file='. $pic .'" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-'. $pics_cols .' col-lg-4"';
				if($media instanceof rex_media) {
					print ' data-title="'. $media->getValue('title') .'"';
				}
				print '>';
                print '<img src="index.php?rex_media_type='. $type_thumb .'&rex_media_file='. $pic .'" class="img-fluid gallery-pic-box"';
				if($media instanceof rex_media) {
					print ' alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'"';
				}
				print '>';
				print '</a>';
			}
		?>
	</div>
</div>
<script>
	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox({
			alwaysShowClose: true
		});
	});
</script>