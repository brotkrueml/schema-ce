# Structured data for TYPO3 with the schema_records extension via records

[![CI Status](https://github.com/brotkrueml/schema-records/workflows/CI/badge.svg?branch=master)](https://github.com/brotkrueml/schema-records/actions?query=workflow%3ACI)


## Requirements

The extension works with TYPO3 9 LTS.


## Introduction

This extension builds upon the [schema extension](https://github.com/brotkrueml/schema) (which
offers an API and view helpers) and gives the possibility to add schema.org vocabulary with records.
The records can be placed on every page and enriches the web page with
the needed markup - dependent on the user rights also for editors.

The extension has an alpha status, for now it is a proof of concept.
Code changes must not be backward compatible. However, the extension works
and could be used at own risk!

You are welcome to participate!


## Installation

### Installation With Composer

The recommended way to install this extension is by using Composer. In your Composer based TYPO3 project root, just type

    composer req brotkrueml/schema-reports

### Installation As An Extension From The TYPO3 Extension Repository (TER)

Not available yet.


## Configuration

### Reduce list of available types

You can reduce the list of available types in the type record (currently over 500) with PageTS.

For example:

    TCEFORM.tx_schemarecords_domain_model_type.schema_type.keepItems = Person,Place,Corporation

or

    TCEFORM.tx_schemarecords_domain_model_type.schema_type.removeItems := addToList(Airport,Casino)
