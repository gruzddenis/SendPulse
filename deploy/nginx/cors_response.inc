if ($request_method = 'OPTIONS') {
    add_header 'Access-Control-Allow-Origin' '*' always;
    add_header 'Access-Control-Allow-Methods' 'PUT, GET, POST, DELETE, OPTIONS, PATCH' always;
    add_header 'Access-Control-Allow-Headers' 'Origin, X-Requested-With, Content-Type, Accept, Auth-Token' always;
    add_header 'Access-Control-Max-Age' 3600;
    add_header 'Content-Type' 'text/plain charset=UTF-8' always;
    add_header 'Content-Length' 0 always;
    return 204;
}

if ($request_method ~ ^(GET|POST|PUT|PATCH|DELETE)$ ) {
    add_header 'Access-Control-Allow-Origin' '*' always;
    add_header 'Access-Control-Allow-Methods' 'HEAD, GET, POST, PUT, PATCH, DELETE' always;
    add_header 'Access-Control-Allow-Headers' 'Origin, X-Requested-With, Content-Type, Accept, Auth-Token' always;
    add_header 'Access-Control-Expose-Headers' 'Origin, X-Requested-With, Content-Type, Accept, Auth-Token' always;
    add_header 'X-Proxy-Cache' $upstream_cache_status;
}
