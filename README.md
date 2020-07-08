![PHP Composer](https://github.com/stephanediondev/elasticsearch-admin/workflows/PHP%20Composer/badge.svg)

## Requirements

- Elasticsearch 6.x or 7.x (https://www.elastic.co/guide/en/elasticsearch/reference/current/install-elasticsearch.htm)
- Web server with rewrite module enabled
- PHP 7.2.5 or higher (https://symfony.com/doc/current/setup/web_server_configuration.html)
- Composer (https://getcomposer.org/download/)
- npm (https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

## Installation

Configure a vhost with the document root set to "public" folder (ie /var/www/elasticsearch-admin/public)

```
cd /var/www/elasticsearch-admin/

composer install

npm install
npm run build

bin/console security:encode-password
# Encode a password

cp .env.dist .env
# Edit ELASTICSEARCH_URL, EMAIL and ENCODED_PASSWORD
# If Elasticsearch security features are enabled, edit ELASTICSEARCH_USERNAME and ELASTICSEARCH_PASSWORD
```

## Features

- [x] Connection to Elasticsearch: server-side (no CORS config), local (private) or remote server, http or https, with credentials or not
- [x] Login: user managed by Symfony, not related to Elasticsearch
- [x] Cluster: basic metrics, allocation explain, list settings, update settings (transient or persistent)
- [x] Nodes: list, read, usage, plugins, reload secure settings [6.4]
- [x] Indices: list, reindex, create, read, update (mappings), lifecycle [6.6] (explain, remove policy), delete, close / open, freeze / unfreeze [6.6], force merge, clear cache, flush, refresh, empty, import (XLSX) / export (CSV, TSV, ODS, XLSX, GEOJSON), aliases (list, create, delete)
- [x] Index templates (legacy): list, create, read, update, delete, copy
- [x] Index templates [7.8]: list, create, read, update, delete, copy
- [x] Component templates [7.8]: list, create, read, update, delete, copy
- [x] Index lifecycle management policies [6.6]: list, status, start, stop, create, read, update, delete, copy
- [x] Shards: list
- [x] Repositories: list, create (fs, s3, gcs), read, update, delete, cleanup, verify
- [x] Snapshot lifecycle management policies [7.4]: list, status, start, stop, create, read, update, delete, execute, history, stats, copy
- [x] Snapshots: list, create, read, delete, failures, restore
- [x] Users (native realm): list, create, read, update, delete, enable, disable
- [x] Roles: list, create, read, update, delete, copy
- [x] Tasks: list
- [x] Remote clusters: list
- [x] Enrich policies [7.5]: list, stats, create, read, delete, execute, copy
- [x] Pipelines: list, create, read, update, delete, copy
- [x] Cat APIs: list, export (CSV, TSV, ODS, XLSX)
- [x] Console
- [x] Deprecations info
- [x] License: read, status / start trial / revert to basic [6.6], features

## Todo

- [ ] Docker image
- [ ] Users management with roles and permissions, not related to Elasticsearch
- [ ] Indices: update (dynamic index settings), shrink, split, import from database
- [ ] Index templates (legacy): convert to new version
- [ ] Repositories: create (url, source, hdfs, azure)
- [ ] Remote clusters: create, update, delete
- [ ] License: update

## Unit tests

```
bin/console app:phpunit && bin/phpunit
```

## Screenshots

[![Login](assets/images/resized-login.png)](assets/images/original-login.png)

[![Cluster](assets/images/resized-cluster.png)](assets/images/original-cluster.png)

[![Cluster settings](assets/images/resized-cluster-settings.png)](assets/images/original-cluster-settings.png)

[![Cluster allocation explain](assets/images/resized-cluster-allocation-explain.png)](assets/images/original-cluster-allocation-explain.png)

[![Nodes](assets/images/resized-nodes.png)](assets/images/original-nodes.png)

[![Node](assets/images/resized-node.png)](assets/images/original-node.png)

[![Indices](assets/images/resized-indices.png)](assets/images/original-indices.png)

[![Indices stats](assets/images/resized-indices-stats.png)](assets/images/original-indices-stats.png)

[![Index](assets/images/resized-index.png)](assets/images/original-index.png)

[![Create index](assets/images/resized-index-create.png)](assets/images/original-index-create.png)

[![Index templates](assets/images/resized-index-templates.png)](assets/images/original-index-templates.png)

[![Create index template](assets/images/resized-index-template-create.png)](assets/images/original-index-template-create.png)

[![Shards](assets/images/resized-shards.png)](assets/images/original-shards.png)

[![Create AWS S3 repository](assets/images/resized-repository-create-s3.png)](assets/images/original-repository-create-s3.png)

[![Create SLM policy](assets/images/resized-slm-policy-create.png)](assets/images/original-slm-policy-create.png)

[![Snaphosts](assets/images/resized-snapshots.png)](assets/images/original-snapshots.png)

[![Create snapshot](assets/images/resized-snapshot-create.png)](assets/images/original-snapshot-create.png)

[![Create enrich policy](assets/images/resized-enrich-create.png)](assets/images/original-enrich-create.png)

[![License](assets/images/resized-license.png)](assets/images/original-license.png)
