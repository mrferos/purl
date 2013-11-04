purl
====

### A shameless port of the furl python library to PHP to make URL manipulation easy.

#### The Query

Manipulating the url query is easy:
```php
$url = \Purl\Purl::fromString('http://www.google.com/?one=1&two=2');
unset($url['one']);
$url['three'] = 'foo';
echo $url->toString();
// http://www.google.com/?two=2&three=foo
````

Alternatively you can do the same like so:
```php
$url = \Purl\Purl::fromString('http://www.google.com/?one=1&two=2');
$url->getQuery()->add('three','foo')
                ->remove('one');
echo $url;
// http://www.google.com/?two=2&three=foo
```

Still one more way since the query has easy accessor methods in the Purl object:
```php
$url = \Purl\Purl::fromString('http://www.google.com/?one=1&two=2');
$url->add('three', 'foo')
    ->remove('one');
echo $url;
// http://www.google.com/?two=2&three=foo
```

### The Path

You can add or remove from the path like so:
```php
$url = \Purl\Purl::fromString('http://www.google.com/path/?foo=2');
$url->getPath()->add('second-part');
// 'http://www.google.com/path/second-part/?foo=2'
$url->getPath()->remove('path');
// http://www.google.com/second-part/?foo=2
```

### The Fragment

Fragments can be edited like so:
```php
$url = \Purl\Purl::fromString('http://www.google.com/path/?foo=2#fragment/foo?arg=one');
$url->getFragment()->getQuery()->remove('arg');
// http://www.google.com/path/?foo=2#fragment/foo
$url->getFragment()->getPath()->remove('foo');
// http://www.google.com/path/?foo=2#fragment
```




[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/mrferos/purl/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

