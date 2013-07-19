<?php
/*
 * Plugin Name: Libre support for Term Archives
 * Plugin URI: http://wordpress.lowtone.nl/plugins/terms-archive-libre/
 * Description: Add terms to the Libre document.
 * Version: 1.0
 * Author: Lowtone <info@lowtone.nl>
 * Author URI: http://lowtone.nl
 * License: http://wordpress.lowtone.nl/license
 */
/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2013, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\plugins\lowtone\terms\archive\libre
 */

namespace lowtone\terms\archive\libre {

	use lowtone\content\packages\Package,
		lowtone\wp\taxonomies\Taxonomy,
		lowtone\wp\terms\collections\Collection;

	// Includes
	
	if (!include_once WP_PLUGIN_DIR . "/lowtone-content/lowtone-content.php") 
		return trigger_error("Lowtone Content plugin is required", E_USER_ERROR) && false;

	$__i = Package::init(array(
			Package::INIT_PACKAGES => array("lowtone", "lowtone\\wp"),
			Package::INIT_MERGED_PATH => __NAMESPACE__,
			Package::INIT_SUCCESS => function() {

				add_action("init", function() {
					if (!function_exists("lowtone\\libre\\filterName"))
						return;

					add_filter(\lowtone\libre\filterName("append_templates"), function($templates) {
						$templates[] = apply_filters("lowtone_terms_archive_libre_template", realpath(__DIR__ . "/assets/templates/index.xsl"));

						return $templates;
					});

					add_filter(\lowtone\libre\filterName("document_options"), function($options) {
						if (!check())
							return $options;

						$options = array_merge(array(
								"build_taxonomy" => true,
								"build_query" => false,
							), (array) $options);

						return $options;
					}, 0);

					add_action("build_" . \lowtone\libre\filterName("document"), function($document) {
						global $wp_query;

						if (!$document->getBuildOption("build_taxonomy"))
							return;

						$taxonomy = new Taxonomy($wp_query->taxonomy);

						$taxonomyDocument = $taxonomy
							->__toDocument()
							->build();

						if ($taxonomyElement = $document->importDocument($taxonomyDocument))
							$document->documentElement->appendChild($taxonomyElement);

						$terms = Collection::create($taxonomy->terms);

						$termsDocument = $terms
							->__toDocument()
							->build();

						if ($termsElement = $document->importDocument($termsDocument))
							$taxonomyElement->appendChild($termsElement);

					});
				});

			}
		));

	function check() {
		global $wp_query;

		if (!$wp_query->get("lowtone_terms_archive_taxonomy"))
			return false;

		if (!isset($wp_query->taxonomy))
			return false;

		return true;
	}

}