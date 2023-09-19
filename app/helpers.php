<?php

/**
 * Check whether the url/path is match to the current route
 */
if (! function_exists('route_named')) {
    function route_named(string $path): bool
    {
        return request()->route()->named($path);
    }
}
