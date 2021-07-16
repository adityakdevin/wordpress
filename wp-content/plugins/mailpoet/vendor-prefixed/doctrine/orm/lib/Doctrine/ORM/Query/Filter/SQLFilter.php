<?php
 namespace MailPoetVendor\Doctrine\ORM\Query\Filter; if (!defined('ABSPATH')) exit; use MailPoetVendor\Doctrine\DBAL\Connection; use MailPoetVendor\Doctrine\DBAL\Types\Type; use MailPoetVendor\Doctrine\DBAL\Types\Types; use MailPoetVendor\Doctrine\ORM\EntityManagerInterface; use MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata; use MailPoetVendor\Doctrine\ORM\Query\ParameterTypeInferer; use InvalidArgumentException; use RuntimeException; use function array_map; use function implode; use function is_array; use function ksort; use function serialize; abstract class SQLFilter { private $em; private $parameters = []; public final function __construct(\MailPoetVendor\Doctrine\ORM\EntityManagerInterface $em) { $this->em = $em; } public final function setParameterList(string $name, array $values, string $type = \MailPoetVendor\Doctrine\DBAL\Types\Types::STRING) : self { $this->parameters[$name] = ['value' => $values, 'type' => $type, 'is_list' => \true]; \ksort($this->parameters); $this->em->getFilters()->setFiltersStateDirty(); return $this; } public final function setParameter($name, $value, $type = null) : self { if ($type === null) { $type = \MailPoetVendor\Doctrine\ORM\Query\ParameterTypeInferer::inferType($value); } $this->parameters[$name] = ['value' => $value, 'type' => $type, 'is_list' => \false]; \ksort($this->parameters); $this->em->getFilters()->setFiltersStateDirty(); return $this; } public final function getParameter($name) { if (!isset($this->parameters[$name])) { throw new \InvalidArgumentException("Parameter '" . $name . "' does not exist."); } if ($this->parameters[$name]['is_list']) { throw \MailPoetVendor\Doctrine\ORM\Query\Filter\FilterException::cannotConvertListParameterIntoSingleValue($name); } $param = $this->parameters[$name]; return $this->em->getConnection()->quote($param['value'], $param['type']); } public final function getParameterList(string $name) : string { if (!isset($this->parameters[$name])) { throw new \InvalidArgumentException("Parameter '" . $name . "' does not exist."); } if ($this->parameters[$name]['is_list'] === \false) { throw \MailPoetVendor\Doctrine\ORM\Query\Filter\FilterException::cannotConvertSingleParameterIntoListValue($name); } $param = $this->parameters[$name]; $connection = $this->em->getConnection(); $quoted = \array_map(static function ($value) use($connection, $param) { return $connection->quote($value, $param['type']); }, $param['value']); return \implode(',', $quoted); } public final function hasParameter($name) { return isset($this->parameters[$name]); } public final function __toString() { return \serialize($this->parameters); } protected final function getConnection() : \MailPoetVendor\Doctrine\DBAL\Connection { return $this->em->getConnection(); } public abstract function addFilterConstraint(\MailPoetVendor\Doctrine\ORM\Mapping\ClassMetadata $targetEntity, $targetTableAlias); } 