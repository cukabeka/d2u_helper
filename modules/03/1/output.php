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
	$type = "REX_VALUE[1]";
?>
<div class="col-12 col-sm-<?php echo $cols . $offset_lg; ?>">
<?php
	if ("REX_MEDIA[1]" != '') {
		$media = rex_media::get("REX_MEDIA[1]");
		print '<img src="index.php?rex_media_type='. $type .'&rex_media_file=REX_MEDIA[1]" alt="'. 
			$media->getValue('title') .'" title="'. $media->getValue('title') .'">';
	}
?>
</div>