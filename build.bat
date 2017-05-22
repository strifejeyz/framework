@echo off

del storage\sessions\* /q /f
del storage\logs\* /q /f
del storage\backups\* /q /f
composer dump-autoload
git add *
git commit -m "..."