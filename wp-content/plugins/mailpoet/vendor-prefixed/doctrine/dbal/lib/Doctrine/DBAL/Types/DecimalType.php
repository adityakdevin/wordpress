<?php
 namespace MailPoetVendor\Doctrine\DBAL\Types; if (!defined('ABSPATH')) exit; use MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform; class DecimalType extends \MailPoetVendor\Doctrine\DBAL\Types\Type { public function getName() { return \MailPoetVendor\Doctrine\DBAL\Types\Types::DECIMAL; } public function getSQLDeclaration(array $column, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform) { return $platform->getDecimalTypeDeclarationSQL($column); } public function convertToPHPValue($value, \MailPoetVendor\Doctrine\DBAL\Platforms\AbstractPlatform $platform) { return $value; } } 