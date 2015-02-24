# freeostorrent

PRESENTATION :
Front-end php + MySQL (PDO) pour XBT tracker (bittorrent)
freeostorrent.fr est un projet visant la création d'un front-end php "from-scratch" à XBT Tracker de Olaf Van Der Spek. 
freeostorrent.fr est issu de la famille de freetorrent.fr...

PREREQUIS :
Gnu/Linux - Nginx - MySQL - PHP

ETAPES DE CONFIGURATION :
1 - Installer xbt tracker (http://xbtt.sourceforge.net/tracker/) - Vous trouverez plein d'infos également ici --> http://visigod.com/xbt-tracker/table-documentation
2 - Installer les fichiers freeostorrent à la racine de votre site web. Mettez les permissions à 0777 sur certains repertaoires : torrents/, images/ ... et 0755 sur les autres répertoires.
3 - Installer la base de données MySQL en la modifiant selon vos besoins...
4 - Installer le crontab (crontab -e) tel que présenté dans le fichier crontab.txt. Une fois le crontab installé, vous pouvez supprimer ce fichier crontab.txt.
5 - Ce projet tourne actuellement sous Nginx. Voici une partie du fichier nginx pour le site :
 location / {
            root /var/www/monsiteamoi/web;
            rewrite ^/c-(.*)$ /catpost.php?id=$1 last;
            rewrite ^/a-(.*)-(.*)$ /archives.php?month=$1&year=$2 last;
            if (!-d $request_filename){
            set $rule_2 1$rule_2;
            }
            if (!-f $request_filename){
            set $rule_2 2$rule_2;
            }
            if ($rule_2 = "21"){
            rewrite ^/(.*)$ /viewpost.php?id=$1 last;
            }
            include /etc/nginx/conf.d/php;
            include /etc/nginx/conf.d/cache;
            #satisfy any;
            #allow all;
        }

Site web d'origine : http://www.freeostorrent.fr
