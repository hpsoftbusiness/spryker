<?php

if (!empty(getenv('SPRYKER_CLUSTER'))) {
    return require('clusters/' . getenv('SPRYKER_CLUSTER') . '/default_store.php');
} else {
    return getenv('SPRYKER_DEFAULT_STORE') ?: 'DE';
}
