# Structured data for TYPO3 with the schema_records extension via records

[![CI Status](https://github.com/brotkrueml/schema-records/workflows/CI/badge.svg?branch=master)](https://github.com/brotkrueml/schema-records/actions?query=workflow%3ACI)
[![Coverage Status](https://coveralls.io/repos/github/brotkrueml/schema-records/badge.svg?branch=master)](https://coveralls.io/github/brotkrueml/schema-records?branch=master)


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

There are two possibilities to reduce the list of available types and properties
in the type record (currently over 500) with PageTS.

#### With TCEFORM

Use the TCEFORM configuration:

    TCEFORM.tx_schemarecords_domain_model_type.schema_type.keepItems = Person,Place,Corporation

or

    TCEFORM.tx_schemarecords_domain_model_type.schema_type.removeItems := addToList(Airport,Casino)

#### Use Presets

The extension ships some presets (book, course, event, product) which can be used and adjusted. They are
taken from [Google's search gallery](https://developers.google.com/search/docs/guides/search-gallery).
You can also add additional presets. By default, no presets are activated, so all types and properties
are shown.

A preset may look like:

    tx_schemarecords {
      presets {
        terms {
          event {
            types {
              Event = description,endDate,image,location,name,offers,performer,startDate
              Offer = availability,price,priceCurrency,validFrom,url
              PerformingGroup = name
              Person = name
              Place = address,name
              PostalAddress = addressCountry,addressLocality,addressRegion,postalCode,streetAddress
            }
          }
        }
      }
    }

A preset is defined in the `tx_schemarecords.presets.terms` PageTS. In the example `event`
(all lowercase) is a key to reference later on - you can name it to your needs. Then
define the types with the according properties that are needed. In the example
`Event` is the type and maps to the schema.org type http://schema.org/Event. The properties
`description,endDate,image,location,name,offers,performer,startDate` are shown in the record.
As a property may connect to another type (like `performer` to `Person` or `Performing Group`)
these are also defined in this special preset.

You can activate presets (e.g. `book` and `event`) like this:

    tx_schemarecords {
      presets {
        activeTerms = book,event
      }
    }

Have a look into the folder `Configuration/TSconfig/Page/Presets/` for more information. By now,
the presets

   * [book](https://developers.google.com/search/docs/data-types/book)
   * [course](https://developers.google.com/search/docs/data-types/course)
   * [event](https://developers.google.com/search/docs/data-types/event)
   * [product](https://developers.google.com/search/docs/data-types/product)

are shipped with the extension.

Define your own presets, e.g., like this:

    tx_schemarecords {
      presets {
        activeTerms = tx_myext_event,product
        terms {
          tx_myext_event {
              Event = description,endDate,image,location,name,offers,organizer,performer,sponsor,startDate
              Offer = availability,price,priceCurrency,validFrom,url
              PerformingGroup = *
              Person = *
              Place = address,latitude,longitude,name
              PostalAddress = addressCountry,addressLocality,addressRegion,postalCode,streetAddress
          }
        }
      }
    }

As you can see, it is also possible to add all properties to a type with the wildcard
character `*`.

If different presets define the same types with different properties, the union of the
properties are shown for this specific type.
