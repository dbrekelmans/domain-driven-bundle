### Domain Driven Bundle
This bundle helps you configure a domain driven development focussed directory structure. 

## Installation
`composer require dbrekelmans/domain-driven-bundle`

### Routing
Bundle defined routing is never automatically imported in symfony. Importing the routing is as simple as adding the following to your `config/routes.yaml`:
```yaml
framework:
    resource: '@DomainDrivenBundle/config/routes.yaml'
```
_In a future update we could do this for you with a flex recipe._
