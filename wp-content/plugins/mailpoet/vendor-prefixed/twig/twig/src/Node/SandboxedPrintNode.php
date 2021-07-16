<?php
 namespace MailPoetVendor\Twig\Node; if (!defined('ABSPATH')) exit; use MailPoetVendor\Twig\Compiler; use MailPoetVendor\Twig\Node\Expression\ConstantExpression; class SandboxedPrintNode extends \MailPoetVendor\Twig\Node\PrintNode { public function compile(\MailPoetVendor\Twig\Compiler $compiler) { $compiler->addDebugInfo($this)->write('echo '); $expr = $this->getNode('expr'); if ($expr instanceof \MailPoetVendor\Twig\Node\Expression\ConstantExpression) { $compiler->subcompile($expr)->raw(";\n"); } else { $compiler->write('$this->extensions[SandboxExtension::class]->ensureToStringAllowed(')->subcompile($expr)->raw(', ')->repr($expr->getTemplateLine())->raw(", \$this->source);\n"); } } } \class_alias('MailPoetVendor\\Twig\\Node\\SandboxedPrintNode', 'MailPoetVendor\\Twig_Node_SandboxedPrint'); 