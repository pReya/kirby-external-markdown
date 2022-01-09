<?php

Kirby::plugin('preya/kirby-external-markdown', [
  'options' => [
    'cache' => true
  ],
  'tags' => [
    'external' => [
      'attr' => [
        'ttl'
      ],
      'html' => function ($tag) {
        $markdown = null;
        $ttl = intval($tag->ttl) ?? 360;

        var_dump($ttl);

        if (isset($tag->value)) {
          $cache = kirby()->cache('preya.kirby-external-markdown');
          $cacheKey = hash('md5', $tag->value);
          $cachedData = $cache->get($cacheKey);

          if ($cachedData === null) {
            $request = Remote::get($tag->value);
            if ($request->code() === 200) {
              $markdown = $request->content();
              $cache->set($cacheKey, $markdown, $ttl);
            }
          } else {
            $markdown = $cachedData;
          }
        }
        return $markdown;
      }
    ]
  ]
]);
