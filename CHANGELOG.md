# Changelog

## 1.1.0 - 2026-11-10

- `CadsusClientServiceProvider` opcional para Laravel: mescla e publica `config/cadsus.php` (tag `cadsus-client-config`) e registra `CadsusClient`/`CadsusClientInterface` como singleton.
- Suíte de testes passa a usar apenas o `vendor/autoload.php` do próprio pacote.

## 1.0.0 - 2026-11-10

- Fundação inicial do pacote.
- Contratos e DTOs públicos básicos.
- Ambientes e endpoints oficiais.
- Fábrica segura do `SoapClient` com JWT no transporte SOAP.
- Árvore oficial WSDL/XSD e manifesto de integridade.
- Testes unitários, arquiteturais e de contrato.
