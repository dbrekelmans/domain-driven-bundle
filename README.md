# Domain Driven Bundle
This bundle provides automatic bundle configuration for a domain driven directory structure in Symfony.

## Directory structure
The default configuration for this bundle automatically configures the following directory structure:
```
src/
├── <DomainContextA>/
│   ├── Application
│   ├── Domain
│   │   ├── Entity
│   │   ├── Event
│   │   ├── Factory
│   │   ├── Repository
│   │   ├── Service
│   │   └── ValueObject
│   ├── Infrastructure
│   │   └── config
│   │   │   ├── routes.yaml
│   │   │   └── services.yaml
│   └── Presentation
└── <DomainContextB>/
    └── ...
```

## Installation
Install the bundle with composer: `composer require dbrekelmans/domain-driven-bundle`.

### Routing
Bundle defined routing is never automatically imported in symfony. To automatically import the routing configuration from your context directories, add the following to `config/routes.yaml`:
```yaml
framework:
    resource: '@DomainDrivenBundle/Resources/config/routes.yaml'
```

### Configuration
This bundle works out of the works with the directory structure as detailed above, but you can configure 

## Supported bundles
### symfony/framework-bundle
#### Services
Service configuration files are automatically loaded from `domain_driven.directories.context`/*/`domain_driven.directories.infrastructure`/`domain_driven.directories.config`/`domain_driven.files.services`.`{yaml,yml,xml,php}`.

#### Routing
Routing configuration files are automatically loaded from `domain_driven.directories.context`/*/`domain_driven.directories.infrastructure`/`domain_driven.directories.config`/`domain_driven.files.routes`.`{yaml,yml,xml,php}`.

Annotations are not yet supported, but it's on the roadmap.

## Roadmap
### Additional bundles support
* `symfony/framework-bundle` (Route annotations)
* `symfony/twig-bundle`
* `doctrine/doctrine-migrations-bundle`

### Maker command
Console command to create a new context skeleton based on your config. With default configuration will result in the directory structure as detailed above.
