# Structured data for TYPO3 with the schema_records extension via records

[![TYPO3](https://img.shields.io/badge/TYPO3-9%20LTS-orange.svg)](https://typo3.org/)
[![Build Status](https://travis-ci.org/brotkrueml/schema-ce.svg?branch=master)](https://travis-ci.org/brotkrueml/schema-ce)

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

## Current Caveats

IRRE records for the properties of a type are used. The properties can be selected from a list
and are dependent from the selected type.

When a type is newly created, just save it before you add a property. Only then, the specific properties
of that type are shown (otherwise only the properties of type "Thing").

When a property is newly created, only the properties of type "Thing" is shown at first time, because the
parent id of the type is not transferred to a newly created IRRE record. Just use another property name,
save the record and then choose the desired property name.

For the latter, a bug report with a patch is available:
[https://forge.typo3.org/issues/63777](https://forge.typo3.org/issues/63777)

But as this patch hasn't found its way into the core yet (as of TYPO3 9.5.9), you can install
it manually into your composer-based installation with the help of the composer package
"cweagans/composer-patches" (you have to install it first if you don't have it already).
Then just insert into your composer.json an additional block:

    {
        "extra": {
            "patches": {
                "typo3/cms-backend": {
                    "Patch for https://forge.typo3.org/issues/63777": "public/typo3conf/ext/schema_records/Resources/Private/Patches/42b4a06.diff"
                }
            }
        }
    }

As you can see the patch is shipped with this extension. After inserting the block just do a "composer install",
so the patch can be applied.
