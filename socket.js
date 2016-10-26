var echo = require('laravel-echo-server');

var options = {
  appKey: '6l9b99fo148rd5o227flg17njate10ljkso9arotbgpr81kefs2adg7f0r3a',
  authHost: 'http://videoapp53.dev',
  authPath: '/broadcasting/auth',
  host: '192.168.10.10',
  port: 6001,
  devMode: true,
  database: "redis",
};

echo.run(options);
