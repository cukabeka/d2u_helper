<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	$picture = "REX_MEDIA[1]";
	$heading = "REX_VALUE[1]";
	$type = "REX_VALUE[3]";
	$position = "REX_VALUE[4]";
	$same_height = "REX_VALUE[5]" == 'true' ? 'same-height' : '';
	
	$position_container_classes = "col-12 col-md-6 col-lg-". $cols . $offset_lg;
	if($position == "left") {
		$position_container_classes = "col-12 col-lg-". $cols . $offset_lg;
	}
?>
<div class="<?php echo $position_container_classes; ?> abstand">
	<div class="<?php print $same_height; ?> module-box">
		<div class="row">
			<?php
				// Picture
				if($position == "left") {
					print '<div class="col-12 col-sm-6 col-md-4">';
				}
				else {
					print '<div class="col-12">';
				}

				if ("REX_MEDIA[1]" != '') {
					$media = rex_media::get("REX_MEDIA[1]");
					print '<img src="';
					if($type == "") {
						print rex_url::media($picture);
					}
					else {
						print 'index.php?rex_media_type='. $type .'&rex_media_file='. $picture;
					}
					print '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'">';
				}
				
				print '<br><br></div>';

				// Heading and Text
				if($position == "left") {
					print '<div class="col-12 col-sm-6 col-md-8">';
				}
				else {
					print '<div class="col-12">';
				}

				if ($heading != "") {
					print "<b>". $heading ."</b><br>";
				}
				if ('REX_VALUE[id=2 isset=1]') {
					echo "REX_VALUE[id=2 output=html]";
				}

				print '</div>';
			?>
		</div>
	</div>
</div>