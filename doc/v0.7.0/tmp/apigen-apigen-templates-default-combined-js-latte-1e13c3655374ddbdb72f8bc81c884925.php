<?php
// source: /home/greg/MaxMind/GeoIP2-php/vendor/apigen/apigen/templates/default/combined.js.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('0944293590', 'js')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
// ?>

var ApiGen = ApiGen || {};
ApiGen.config = <?php echo Latte\Runtime\Filters::escapeJs($config->template) ?>;

<?php $scripts = array('jquery.min.js', 'jquery.cookie.js', 'jquery.sprintf.js', 'jquery.autocomplete.js', 'jquery.sortElements.js', 'main.js') ;$dir = dirname($template->getFile()) ?>

<?php $iterations = 0; foreach ($scripts as $script) { echo file_get_contents("$dir/js/$script") ?>

<?php $iterations++; } 