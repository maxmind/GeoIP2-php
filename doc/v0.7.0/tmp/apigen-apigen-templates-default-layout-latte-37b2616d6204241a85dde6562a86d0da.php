<?php
// source: /home/greg/MaxMind/GeoIP2-php/vendor/apigen/apigen/templates/default/@layout.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('1412581350', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block group
//
if (!function_exists($_b->blocks['group'][] = '_lbbad2058b9c_group')) { function _lbbad2058b9c_group($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>			<ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($groups) as $group) { $nextLevel = substr_count($iterator->nextValue, '\\') > substr_count($group, '\\') ?>
				<li<?php if ($_l->tmp = array_filter(array($actualGroup === $group || 0 === strpos($actualGroup, $group . '\\') ? 'active' : NULL, $config->main && 0 === strpos($group, $config->main) ? 'main' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>
><a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->groupUrl($group)), ENT_COMPAT) ?>
"><?php echo Latte\Runtime\Filters::escapeHtml($template->subgroupName($group), ENT_NOQUOTES) ;if ($nextLevel) { ?>
<span></span><?php } ?></a>
<?php if ($nextLevel) { ?>
						<ul>
<?php } else { ?>
						</li>
<?php if (substr_count($iterator->nextValue, '\\') < substr_count($group, '\\')) { ?>
							<?php echo $template->repeat('</ul></li>', substr_count($group, '\\') - substr_count($iterator->nextValue, '\\')) ?>

<?php } } $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>
			</ul>
<?php
}}

//
// block elements
//
if (!function_exists($_b->blocks['elements'][] = '_lb8a2c7303ac_elements')) { function _lb8a2c7303ac_elements($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>			<ul>
<?php $iterations = 0; foreach ($elements as $element) { ?>				<li<?php if ($_l->tmp = array_filter(array($activeElement === $element ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>
><a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->elementUrl($element)), ENT_COMPAT) ?>
"<?php if ($_l->tmp = array_filter(array($element->deprecated ? 'deprecated' : NULL, !$element->valid ? 'invalid' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>
><?php if ($namespace) { echo Latte\Runtime\Filters::escapeHtml($element->shortName, ENT_NOQUOTES) ;} else { echo Latte\Runtime\Filters::escapeHtml($element->name, ENT_NOQUOTES) ;} ?></a></li>
<?php $iterations++; } ?>
			</ul>
<?php
}}

//
// end of blocks
//

// template extending

$_l->extends = empty($_g->extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $_g->extended = TRUE;

if ($_l->extends) { ob_start();}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
extract(array('robots' => true), EXTR_SKIP) ;extract(array('active' => ''), EXTR_SKIP) ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="generator" content="<?php echo Latte\Runtime\Filters::escapeHtml($generator, ENT_COMPAT) ?>
 <?php echo Latte\Runtime\Filters::escapeHtml($version, ENT_COMPAT) ?>">
<?php if (!$robots) { ?>	<meta name="robots" content="noindex">
<?php } ?>

	<title><?php Latte\Macros\BlockMacros::callBlock($_b, 'title', $template->getParameters()) ;if ('overview' !== $active && $config->title) { ?>
 | <?php echo Latte\Runtime\Filters::escapeHtml($config->title, ENT_NOQUOTES) ;} ?></title>

<?php $combinedJs = 'resources/combined.js' ?>
	<script type="text/javascript" src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->staticFile($combinedJs)), ENT_COMPAT) ?>"></script>
<?php $elementListJs = 'elementlist.js' ?>
	<script type="text/javascript" src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->staticFile($elementListJs)), ENT_COMPAT) ?>"></script>
<?php $styleCss = 'resources/style.css' ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->staticFile($styleCss)), ENT_COMPAT) ?>">
<?php if ($config->googleCseId) { ?>	<link rel="search" type="application/opensearchdescription+xml" title="<?php echo Latte\Runtime\Filters::escapeHtml($config->title, ENT_COMPAT) ?>
" href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($config->baseUrl), ENT_COMPAT) ?>/opensearch.xml">
<?php } ?>

<?php if ($config->googleAnalytics) { ?>	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', <?php echo Latte\Runtime\Filters::escapeJs($config->googleAnalytics) ?>]);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
<?php } ?>
</head>

<body>
<div id="left">
	<div id="menu">
<?php if ($_l->ifs[] = ('overview' !== $active)) { ?>		<a href="index.html" title="Overview"><?php } ?>
<span>Overview</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>

<?php if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); } ?>

		<div id="groups">
<?php if ($namespaces) { ?>
			<h3>Namespaces</h3>
<?php call_user_func(reset($_b->blocks['group']), $_b, array('groups' => $namespaces, 'actualGroup' => $namespace) + get_defined_vars()) ;} elseif ($packages) { ?>
			<h3>Packages</h3>
<?php call_user_func(reset($_b->blocks['group']), $_b, array('groups' => $packages, 'actualGroup' => $package) + get_defined_vars()) ;} ?>
		</div>

<?php if (($namespaces || $packages) && ($classes || $interfaces || $traits || $exceptions || $constants || $functions)) { ?>		<hr>
<?php } ?>


		<div id="elements">
<?php if ($classes) { ?>
			<h3>Classes</h3>
<?php call_user_func(reset($_b->blocks['elements']), $_b, array('elements' => $classes, 'activeElement' => $class) + get_defined_vars()) ;} ?>

<?php if ($interfaces) { ?>
			<h3>Interfaces</h3>
<?php call_user_func(reset($_b->blocks['elements']), $_b, array('elements' => $interfaces, 'activeElement' => $class) + get_defined_vars()) ;} ?>

<?php if ($traits) { ?>
			<h3>Traits</h3>
<?php call_user_func(reset($_b->blocks['elements']), $_b, array('elements' => $traits, 'activeElement' => $class) + get_defined_vars()) ;} ?>

<?php if ($exceptions) { ?>
			<h3>Exceptions</h3>
<?php call_user_func(reset($_b->blocks['elements']), $_b, array('elements' => $exceptions, 'activeElement' => $class) + get_defined_vars()) ;} ?>

<?php if ($constants) { ?>
			<h3>Constants</h3>
<?php call_user_func(reset($_b->blocks['elements']), $_b, array('elements' => $constants, 'activeElement' => $constant) + get_defined_vars()) ;} ?>

<?php if ($functions) { ?>
			<h3>Functions</h3>
<?php call_user_func(reset($_b->blocks['elements']), $_b, array('elements' => $functions, 'activeElement' => $function) + get_defined_vars()) ;} ?>
		</div>
	</div>
</div>

<div id="splitter"></div>

<div id="right">
<div id="rightInner">
	<form<?php if ($config->googleCseId) { ?> action="http://www.google.com/cse"<?php } ?> id="search">
		<input type="hidden" name="cx" value="<?php echo Latte\Runtime\Filters::escapeHtml($config->googleCseId, ENT_COMPAT) ?>">
		<input type="hidden" name="ie" value="UTF-8">
<?php if ($config->googleCseLabel) { ?>		<input type="hidden" name="more" value="<?php echo Latte\Runtime\Filters::escapeHtml($config->googleCseLabel, ENT_COMPAT) ?>">
<?php } ?>
		<input type="text" name="q" class="text"<?php if ('overview' === $active) { ?>
 autofocus<?php } ?>>
		<input type="submit" value="Search">
	</form>

	<div id="navigation">
		<ul>
			<li<?php if ($_l->tmp = array_filter(array('overview' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('overview' !== $active)) { ?>				<a href="index.html" title="Overview"><?php } ?>
<span>Overview</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php if ($packages) { ?>			<li<?php if ($_l->tmp = array_filter(array('package' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('package' !== $active && $package)) { ?>				<a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->packageUrl($package)), ENT_COMPAT) ?>
" title="Summary of <?php echo Latte\Runtime\Filters::escapeHtml($package, ENT_COMPAT) ?>
"><?php } ?>
<span>Package</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } if ($namespaces) { ?>			<li<?php if ($_l->tmp = array_filter(array('namespace' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('namespace' !== $active && $namespace)) { ?>				<a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->namespaceUrl($namespace)), ENT_COMPAT) ?>
" title="Summary of <?php echo Latte\Runtime\Filters::escapeHtml($namespace, ENT_COMPAT) ?>
"><?php } ?>
<span>Namespace</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } if (!$function && !$constant) { ?>			<li<?php if ($_l->tmp = array_filter(array('class' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('class' !== $active && $class)) { ?>				<a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->classUrl($class)), ENT_COMPAT) ?>
" title="Summary of <?php echo Latte\Runtime\Filters::escapeHtml($class->name, ENT_COMPAT) ?>
"><?php } ?>
<span>Class</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } if ($function) { ?>			<li<?php if ($_l->tmp = array_filter(array('function' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('function' !== $active)) { ?>				<a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->functionUrl($function)), ENT_COMPAT) ?>
" title="Summary of <?php echo Latte\Runtime\Filters::escapeHtml($function->name, ENT_COMPAT) ?>
"><?php } ?>
<span>Function</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } if ($constant) { ?>			<li<?php if ($_l->tmp = array_filter(array('constant' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('constant' !== $active)) { ?>				<a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->constantUrl($constant)), ENT_COMPAT) ?>
" title="Summary of <?php echo Latte\Runtime\Filters::escapeHtml($constant->name, ENT_COMPAT) ?>
"><?php } ?>
<span>Constant</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } ?>
		</ul>
		<ul>
<?php if ($config->tree) { ?>			<li<?php if ($_l->tmp = array_filter(array('tree' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('tree' !== $active)) { ?>				<a href="tree.html" title="Tree view of classes, interfaces, traits and exceptions"><?php } ?>
<span>Tree</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } if ($config->deprecated) { ?>			<li<?php if ($_l->tmp = array_filter(array('deprecated' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('deprecated' !== $active)) { ?>				<a href="deprecated.html" title="List of deprecated elements"><?php } ?>
<span>Deprecated</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } if ($config->todo) { ?>			<li<?php if ($_l->tmp = array_filter(array('todo' === $active ? 'active' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
<?php if ($_l->ifs[] = ('todo' !== $active)) { ?>				<a href="todo.html" title="Todo list"><?php } ?>
<span>Todo</span><?php if (array_pop($_l->ifs)) { ?></a>
<?php } ?>
			</li>
<?php } ?>
		</ul>
		<ul>
<?php if ($config->download) { ?>			<li>
				<a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($archive), ENT_COMPAT) ?>" title="Download documentation as ZIP archive"><span>Download</span></a>
			</li>
<?php } ?>
		</ul>
	</div>

<?php Latte\Macros\BlockMacros::callBlock($_b, 'content', $template->getParameters()) ?>

	<div id="footer">
		<?php echo Latte\Runtime\Filters::escapeHtml($config->title, ENT_NOQUOTES) ?> API documentation generated by <a href="http://apigen.org"><?php echo Latte\Runtime\Filters::escapeHtml($generator, ENT_NOQUOTES) ?>
 <?php echo Latte\Runtime\Filters::escapeHtml($version, ENT_NOQUOTES) ?></a>
	</div>
</div>
</div>
</body>
</html>
