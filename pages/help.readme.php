<div class="panel panel-edit">
	<header class="panel-heading"><div class="panel-title">Allgemeine Hinweise</div></header>
	<div class="panel-body">
		<p>Den Code des Projektes findest du auf GitHub. Und zwar hier: <a href="https://github.com/TobiasKrais/d2u_helper" target="_blank">
			https://github.com/TobiasKrais/d2u_helper</a>.</p>
		<p></p>
	</div>
	<header class="panel-heading"><div class="panel-title">Mobiles Menü in eigenes Template einbinden</div></header>
	<div class="panel-body">
		<p>Beispielseite für Mobiles und Desktop Menü:
			<a href="https://www.design-to-use.de/" target="_blank">https://www.design-to-use.de/</a>.</p>
		<p>Beispiel PHP Code:</p>
		<div style="background-color: #f9f2f4; border: solid 1px #dfe3e9; padding: 15px;">
		<?php
			highlight_string("<div class='navi'>
		<?php
			if(rex_addon::get('d2u_helper')->isAvailable()) {
				d2u_mobile_navi::getResponsiveMultiLevelMobileMenu(); // Mobiles Menü
				d2u_mobile_navi::getResponsiveMultiLevelDesktopMenu(); // Desktop Menü
			}
		?>
	</div>");
		?>
		</div>
	</div>
</div>