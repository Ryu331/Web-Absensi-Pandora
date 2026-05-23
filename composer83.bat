@echo off
set COMPOSER_CACHE_DIR=%~dp0.composer-cache-2
"D:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" -d extension=zip "C:\ProgramData\ComposerSetup\bin\composer.phar" %*
