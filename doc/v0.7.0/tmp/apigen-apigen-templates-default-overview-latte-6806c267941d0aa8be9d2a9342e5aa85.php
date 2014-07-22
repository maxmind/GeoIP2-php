<?php
// source: /home/greg/MaxMind/GeoIP2-php/vendor/apigen/apigen/templates/default/overview.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('0487857967', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block title
//
if (!function_exists($_b->blocks['title'][] = '_lb75ac8cceea_title')) { function _lb75ac8cceea_title($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;echo Latte\Runtime\Filters::escapeHtml($config->title ?: 'Overview', ENT_NOQUOTES) ;
}}

//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb73bdb916a4_content')) { function _lb73bdb916a4_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><div id="content">
	<h1><?php call_user_func(reset($_b->blocks['title']), $_b, get_defined_vars()) ?></h1>

<?php $group = false ?>

<?php if ($namespaces) { ob_start() ?>
	<table class="summary" id="namespaces">
	<caption>Namespaces summary</caption>
<?php $iterations = 0; foreach ($namespaces as $namespace) { if ($config->main && 0 !== strpos($namespace, $config->main)) continue ?>
	<tr>
<?php $group = true ?>
		<td class="name"><a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->namespaceUrl($namespace)), ENT_COMPAT) ?>
"><?php echo Latte\Runtime\Filters::escapeHtml($namespace, ENT_NOQUOTES) ?></a></td>
	</tr>
<?php $iterations++; } ?>
	</table>
<?php if ($iterations) ob_end_flush(); else ob_end_clean(); } ?>

<?php if ($packages) { ob_start() ?>
	<table class="summary" id="packages">
	<caption>Packages summary</caption>
<?php $iterations = 0; foreach ($packages as $package) { if ($config->main && 0 !== strpos($package, $config->main)) continue ?>
	<tr>
<?php $group = true ?>
		<td class="name"><a href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($template->packageUrl($package)), ENT_COMPAT) ?>
"><?php echo Latte\Runtime\Filters::escapeHtml($package, ENT_NOQUOTES) ?></a></td>
	</tr>
<?php $iterations++; } ?>
	</table>
<?php if ($iterations) ob_end_flush(); else ob_end_clean(); } ?>

<?php if (!$group) { $_b->templates['0487857967']->renderChildTemplate('@elementlist.latte', $template->getParameters()) ;} ?>
</div>
<?php
}}

//
// end of blocks
//

// template extending

$_l->extends = '@layout.latte'; $_g->extended = TRUE;

if ($_l->extends) { ob_start();}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
 $active = 'overview' ?>

<?php if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['title']), $_b, get_defined_vars())  ?>


<?php call_user_func(reset($_b->blocks['content']), $_b, get_defined_vars()) ; 