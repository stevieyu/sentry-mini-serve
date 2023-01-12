<?php declare(strict_types=1);
/**
 * @type \FastRoute\RouteCollector $r
 */
$r->post('/api/{appid}/store[/]', function ($args, $post) {
    \App\Store::changeStore($args['appid']);
    return \App\Store::insert($post);
});
$r->get('/api/{appid}/store[/]', function ($args) {
    \App\Store::changeStore($args['appid']);
    if (!count($_GET)) {
        $maxItem = \App\Store::createQueryBuilder()
            ->orderBy(['_id' => 'desc'])
            ->skip(10000)
            ->limit(1)
            ->getQuery()->fetch()[0] ?? [];

        if (count($maxItem) && $maxItem['_id'] ?? '') {
            \App\Store::deleteBy([
                ['_id', '<', $maxItem['_id']]
            ]);
        }
    }

    $queryBuilder = \App\Store::createQueryBuilder();
    $queryBuilder
        ->orderBy(array_merge(
            [
                '_id' => 'desc'
            ],
            $_GET['orderBy'] ?? []
        ));

    if ($_GET['where'] = $_GET['where'] ?? null) $queryBuilder->where($_GET['where']);
    if ($_GET['limit'] = $_GET['limit'] ?? 0) $queryBuilder->limit($_GET['limit']);
    if ($_GET['skip'] = $_GET['skip'] ?? 0) $queryBuilder->skip($_GET['skip']);

    return $queryBuilder->getQuery()->fetch();
});
$r->delete('/api/{appid}/store/{id}', function ($args) {
    \App\Store::changeStore($args['appid']);
    return \App\Store::deleteById($args['id']);
});

$r->get('/api/keys', function ($args) {
    $path = \App\Store::getDatabasePath();
    $res = array_filter(scandir($path), function ($v) {
        return !in_array($v, ['.', '..']);
    });
    return array_values($res);
});

$r->get('/[{any:.+}]', function () {
    echo file_get_contents(__DIR__ . '/view.html');
});