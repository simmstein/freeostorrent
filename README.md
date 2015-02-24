# freeostorrent

<h2>PRESENTATION :</h2>
Front-end php + MySQL (PDO) pour XBT tracker (bittorrent)
freeostorrent.fr est un projet visant la création d'un front-end php "from-scratch" à XBT Tracker de Olaf Van Der Spek. 
freeostorrent.fr est issu de la famille de freetorrent.fr...

<h2>PREREQUIS :</h2>
Gnu/Linux - Nginx - MySQL - PHP

<h2>ETAPES DE CONFIGURATION :</h2>
1 - Installer xbt tracker (http://xbtt.sourceforge.net/tracker/) - Vous trouverez plein d'infos également ici --> http://visigod.com/xbt-tracker/table-documentation<br />
2 - Installer les fichiers freeostorrent à la racine de votre site web. Mettez les permissions à 0777 sur certains repertaoires : torrents/, images/ ... et 0755 sur les autres répertoires.<br />
3 - Compléter / modifier le fichier config.php dans le répertoire includes/
4 - Installer la base de données MySQL en la modifiant selon vos besoins...<br />
5 - Installer le crontab (crontab -e) tel que présenté dans le fichier crontab.txt. Une fois le crontab installé, vous pouvez supprimer ce fichier crontab.txt.<br />
6 - Ce projet tourne actuellement sous Nginx. Voici une partie du fichier nginx pour le site concernant les "rewrite" qui sont très importants :<br />
 location / {<br />
            root /var/www/monsiteamoi/web;<br />
            rewrite ^/c-(.*)$ /catpost.php?id=$1 last;<br />
            rewrite ^/a-(.*)-(.*)$ /archives.php?month=$1&year=$2 last;<br />
            if (!-d $request_filename){<br />
            set $rule_2 1$rule_2;<br />
            }<br />
            if (!-f $request_filename){<br />
            set $rule_2 2$rule_2;<br />
            }<br />
            if ($rule_2 = "21"){<br />
            rewrite ^/(.*)$ /viewpost.php?id=$1 last;<br />
            }<br />
            include /etc/nginx/conf.d/php;<br />
            include /etc/nginx/conf.d/cache;<br />
            #satisfy any;<br />
            #allow all;<br />
        }<br />
<br />
Site web d'origine : http://www.freeostorrent.fr
