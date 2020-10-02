# Troubleshooting

## Schema Upload Error

ref: https://github.com/pantheon-systems/solr-power/issues/355

Probably you won't but if you encounter it, until the fix is merged into next build;

on `_pantheon/web/wp-content/plugins/solr-power/includes/class-solrpower-api.php`

line ~159 find this line
```
if ( 200 === (int) $curl_opts['http_code'] ) { 
```

and replace with 

```
if ( in_array( (int) $curl_opts['http_code'], [ 200, 204 ] ) ) {
```

