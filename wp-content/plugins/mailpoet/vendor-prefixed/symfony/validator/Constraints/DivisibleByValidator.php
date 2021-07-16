<?php
 namespace MailPoetVendor\Symfony\Component\Validator\Constraints; if (!defined('ABSPATH')) exit; use MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedValueException; class DivisibleByValidator extends \MailPoetVendor\Symfony\Component\Validator\Constraints\AbstractComparisonValidator { protected function compareValues($value1, $value2) { if (!\is_numeric($value1)) { throw new \MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedValueException($value1, 'numeric'); } if (!\is_numeric($value2)) { throw new \MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedValueException($value2, 'numeric'); } if (!($value2 = \abs($value2))) { return \false; } if (\is_int($value1 = \abs($value1)) && \is_int($value2)) { return 0 === $value1 % $value2; } if (!($remainder = \fmod($value1, $value2))) { return \true; } return \sprintf('%.12e', $value2) === \sprintf('%.12e', $remainder); } protected function getErrorCode() { return \MailPoetVendor\Symfony\Component\Validator\Constraints\DivisibleBy::NOT_DIVISIBLE_BY; } } 