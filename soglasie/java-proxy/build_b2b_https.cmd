@echo off

set ANT_HOME=C:\Oracle\Middleware\jdeveloper\ant
set ANT_OPTS=-Xmx256M

set PATH=%ANT_HOME%\bin;%PATH%
set CUR_DIR=%CD%

echo --- set wl-env
call %JDEV_USER_DIR%\system11.1.2.3.39.62.76.1\DefaultDomain\bin\setDomainEnv.cmd

echo --- generate lib
cd %CUR_DIR%
call ant -f build_b2b_https.xml generate

pause
