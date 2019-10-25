rm -rf ./storage/*
rm -rf ./logs/*
rm -rf ./backups/*
php composer dump-autoload
git add *
git commit -m "..."