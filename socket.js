var echo = require('laravel-echo-server');
require('dotenv').config();

var options = {
  appKey: '6l9b99fo148rd5o227flg17njate10ljkso9arotbgpr81kefs2adg7f0r3a',
  authHost: process.env.APP_URL,
  authPath: '/broadcasting/auth',
  host: process.env.SOCKET_IO_IP,
  port: 6001,
  devMode: true,
  database: "redis",
};

echo.run(options);
