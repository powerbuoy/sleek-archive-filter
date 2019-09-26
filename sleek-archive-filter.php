<?php
namespace Sleek\ArchiveFilter;

if (get_theme_support('sleek-archive-filter')) {
	add_filter('pre_get_posts', function ($query) {
		# Only touch main query
		if (!is_admin() and $query->is_main_query()) {
			# Build potential tax and meta query
			$taxQuery = $query->get('tax_query', ['relation' => 'AND']);
			$metaQuery = $query->get('meta_query', ['relation' => 'AND']);
			$hasTaxQuery = false;
			$hasMetaQuery = false;

			# Go through all get params
			foreach ($_GET as $k => $v) {
				# If this is a sleek filter taxonomy
				if (substr($k, 0, strlen('sleek_filter_tax_')) === 'sleek_filter_tax_') {
					$tax = substr($k, strlen('sleek_filter_tax_'));
					$val = $_GET[$k];
					$val = is_array($val) ? array_filter($val) : array_filter([$val]);

					if (!empty($val)) {
						$hasTaxQuery = true;
						$taxQuery[] = [
							'taxonomy' => $tax,
							'field' => 'term_id',
							'terms' => $val
						];
					}
				}
				# Or a sleek filter meta min query
				elseif (substr($k, 0, strlen('sleek_filter_meta_min_')) === 'sleek_filter_meta_min_') {
					$meta = substr($k, strlen('sleek_filter_meta_min_'));
					$val = $_GET[$k];
					$val = is_array($val) ? array_filter($val) : array_filter([$val]);

					if (!empty($val)) {
						$hasMetaQuery = true;

						foreach ($val as $v) {
							$metaQuery[] = [
								'key' => $meta,
								'value' => $v,
								'compare' => '>=',
								'type' => is_numeric($v) ? 'NUMERIC' : 'CHAR'
							];
						}
					}
				}
				# Max query
				elseif (substr($k, 0, strlen('sleek_filter_meta_max_')) === 'sleek_filter_meta_max_') {
					$meta = substr($k, strlen('sleek_filter_meta_max_'));
					$val = $_GET[$k];
					$val = is_array($val) ? array_filter($val) : array_filter([$val]);

					if (!empty($val)) {
						$hasMetaQuery = true;

						foreach ($val as $v) {
							$metaQuery[] = [
								'key' => $meta,
								'value' => $v,
								'compare' => '<=',
								'type' => is_numeric($v) ? 'NUMERIC' : 'CHAR'
							];
						}
					}
				}
				# Equal query
				elseif (substr($k, 0, strlen('sleek_filter_meta_')) === 'sleek_filter_meta_') {
					$meta = substr($k, strlen('sleek_filter_meta_'));
					$val = $_GET[$k];
					$val = is_array($val) ? array_filter($val) : array_filter([$val]);

					if (!empty($val)) {
						$hasMetaQuery = true;

						foreach ($val as $v) {
							$metaQuery[] = [
								'key' => $meta,
								'value' => $v,
								'compare' => '=',
								'type' => is_numeric($v) ? 'NUMERIC' : 'CHAR'
							];
						}
					}
				}
			}

			if ($hasTaxQuery) {
				$query->set('tax_query', $taxQuery);
			}
			if ($hasMetaQuery) {
				$query->set('meta_query', $metaQuery);
			}

			# See if a search string is provided
			if (isset($_GET['sleek_filter_search'])) {
				$query->set('s', $_GET['sleek_filter_search']);
			}
		}
	});
}
