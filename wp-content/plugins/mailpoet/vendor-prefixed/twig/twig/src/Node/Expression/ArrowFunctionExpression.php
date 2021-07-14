<?php
 namespace MailPoetVendor\Twig\Node\Expression; if (!defined('ABSPATH')) exit; use MailPoetVendor\Twig\Compiler; use MailPoetVendor\Twig\Node\Node; class ArrowFunctionExpression extends \MailPoetVendor\Twig\Node\Expression\AbstractExpression { public function __construct(\MailPoetVendor\Twig\Node\Expression\AbstractExpression $expr, \MailPoetVendor\Twig\Node\Node $names, $lineno, $tag = null) { parent::__construct(['expr' => $expr, 'names' => $names], [], $lineno, $tag); } public function compile(\MailPoetVendor\Twig\Compiler $compiler) { $compiler->addDebugInfo($this)->raw('function ('); foreach ($this->getNode('names') as $i => $name) { if ($i) { $compiler->raw(', '); } $compiler->raw('$__')->raw($name->getAttribute('name'))->raw('__'); } $compiler->raw(') use ($context, $macros) { '); foreach ($this->getNode('names') as $name) { $compiler->raw('$context["')->raw($name->getAttribute('name'))->raw('"] = $__')->raw($name->getAttribute('name'))->raw('__; '); } $compiler->raw('return ')->subcompile($this->getNode('expr'))->raw('; }'); } } 