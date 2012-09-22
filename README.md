## PHP MongoDB Driver Configuration

On Mac OS X with XAMPP:
```shell
$ pecl download mongo
$ tar -xf mongo-1.2.12.tgz
$ cd mongo-1.2.12
$ sudo /Applications/XAMPP/xamppfiles/bin/phpize
$ sudo MACOSX_DEPLOYMENT_TARGET=10.7 CFLAGS="-arch i386 -arch x86_64 -g -Os -pipe -no-cpp-precomp" CCFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" CXXFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" LDFLAGS="-arch i386 -arch x86_64 -bind_at_load" ./configure --with-apxs=/Applications/XAMPP/xamppfiles/bin/apxs --with-php-config=/Applications/XAMPP/xamppfiles/bin/php-config
$ sudo make
$ sudo make install
```

Finally enable it in `php.ini`: (e.g. `/Applications/XAMPP/xamppfiles/etc/php.ini`)
```ini
extension=mongo.so
```
