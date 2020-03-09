<table class="table">
	<thead>
		<tr>
			<th>Link text</th>
			<th>URL</th>
		</tr>
	</thead>
	<tbody>
		<?php
		( function() {
			$links = internal_crawling_get_internal_links(); // From inc/functions/crawl-results.php.
			foreach ( $links as $link ) {
				echo wp_kses_post( '<tr><td>' . $link['text'] . '</td><td><a href="' . $link['href'] . '" target="_blank">' . $link['href'] . '</a></td></tr>' );
			}
		} )();
?>
	</tbody>
</table>
