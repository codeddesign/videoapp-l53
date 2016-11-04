var echo = require('laravel-echo-server');
require('dotenv').config();

var options = {
  appKey: '6l9b99fo148rd5o227flg17njate10ljkso9arotbgpr81kefs2adg7f0r3a',
  authHost: process.env.APP_URL,
  authPath: '/broadcasting/auth',
  host: '0.0.0.0',
  port: 3000,
  devMode: process.env.APP_DEBUG,
  database: "redis",
};

echo.run(options);
