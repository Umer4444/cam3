TO INSTALL MAGENTO

1. delete vendor/magento
2. make sure nginx config is included
3. run this, replacing xxx with your params: 

bin/magento setup:install --db-host=$(printenv MYSQL_PORT_3306_TCP_ADDR) --db-name=store --db-user=root --db-password=$(printenv MYSQL_ENV_MYSQL_ROOT_PASSWORD) --backend-frontname=admin --admin-user=admin --admin-password=test123 --admin-email=debug@perfectweb.ro --admin-firstname=admin --admin-lastname=lastname --base-url=http://store.xexposed.com

docker

docker run -v $(pwd)/server/nginx/rtmp.conf:/usr/local/nginx/conf/nginx.conf -p 80:80 -p 1935:1935 --name nginx -dit nginx-rtmp
docker run -dit -p 843:843 -p 1935:1935 -p 5080:5080 -p 5443:5443 -p 8081:8081 -p 8443:8443 --name=red5 red5:latest


data fixtures

1. users are created with random role, all have username concatenated from `role_id[concat]incr` and email as 
username@test.com
2. all users with `incr` < 3 are activated, the others are random activated
3. all users have as password `test123`


helpers

1. if the route contains a variable named `user` then the helpers would automatically detect and use the user 
which has the `id` = `user`
2. a view helper's class should be named `Application\View\Helper\Rating` and registered as `rating` in the invokables


views

1. if the route contains a variable named `entity_album_id_Images\Entity\Albums` = 547 then the view would 
automatically be injected with the `album` which has the `id` = `547`; where as
    entity = just for check; mandatory for autoassign
    album = the name of the view variable
    id = the key from which the fetch will be done
    Images\Entity\Albums = the entity


controllers

1. a controller should be named Application\Controller\IndexController and registered as 
`Application\Controller\IndexController::class` in the invokables both as key and value 


GAMES

1. checkers
docker run -dit -p 5000:5000 --name=checkers perfectweb/checkers /usr/local/games/checkers/checkers/unthreaded_server.py --port 5000 --prune-inactive 500


jquery
->setAttribute('data-type', 'datetime');