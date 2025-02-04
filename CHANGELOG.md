# CHANGELOG

## [1.3.1] - 2025-02-04

### Added

- supported `symfony/expression-language` 7.0 version info

### Changed

- migrate tests to phpunit 9.6

## [1.3.0] - 2022-06-27

### Added

- invoked with named method adds method name and arguments array
  to execution context, which allows use evaluator as common proxy

## [1.2.0] - 2022-05-21

### Changed

- catch exceptions and error to exception options moved from constructor
  to method

### Added

- optional mapping names of invoke arguments context variables in place
  of default `arg0`, `arg1`, etc.
- catch any method call as evaluator invoke method

## [1.1.0] - 2022-05-16

### Added

- exception and error handling: 
  - catch exceptions option
  - error to exception conversion option
