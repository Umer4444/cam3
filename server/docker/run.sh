#!/usr/bin/env bash

environment='production'
sshPort='1022'
httpPort='80'
debugPort='9001'
path=$(pwd)
user=$(whoami)

while getopts ':e:' flag; do
  case "${flag}" in
    e) environment="${OPTARG}";;
  esac
done

if [ $environment = 'development' ]; then
    sshPort=$(shuf -i 5000-6000 -n 1)
    httpPort=$(shuf -i 5000-6000 -n 1)
    debugPort=$(shuf -i 5000-6000 -n 1)
fi

while getopts ':e:s:h:d:u:p:' flag; do
  case "${flag}" in
    e) environment="${OPTARG}";;
    s) sshPort="${OPTARG}";;
    h) httpPort="${OPTARG}" ;;
    d) debugPort="${OPTARG}" ;;
    p) path="${OPTARG}" ;;
    u) user="${OPTARG}" ;;
    *) echo "Unexpected option ${flag}"; exit ;;
  esac
done

volumes="-v ${path}/server/nginx:/etc/nginx/camclients -v ${path}/server/nginx/${environment}/nginx.conf:/etc/nginx/vhosts/vhost.conf -v ${path}:/var/www/html/camclients"
envVariables="-e INIT_SCRIPTS=/root/html/camclients/init.sh; -e APPLICATION_ENV=${environment} -e START_MYSQL=false -e PREFIX=${user}"

if [ $environment = 'development' ]
then

    echo "SSH port: ${sshPort}"
    echo "Debug port: ${debugPort}"

    sudo docker run -dit --name ${user}_mysql -e MYSQL_ROOT_PASSWORD=root mysql:5.7
    sudo docker start ${user}_mysql

    containerName="${user}_camclients_${environment}"
    sudo docker run -dit --name=${containerName} ${envVariables} --link ${user}_mysql:${user}_mysql -p ${sshPort}:22 -p ${debugPort}:9001 -p ${httpPort}:80 ${volumes} perfectweb/development:master

elif [ $environment = 'production' ] || [ $environment = 'staging' ]
then

    sudo docker run -dit --name mysql -e MYSQL_ROOT_PASSWORD=root mysql:5.7
    sudo docker start mysql

    containerName="camclients_${environment}"
    sudo docker run -dit --name=${containerName} ${envVariables} --link mysql:mysql -p ${httpPort}:80 ${volumes} perfectweb/production:master

else
    echo "This environment doesn't exit !"
    exit
fi

echo "Web port: ${httpPort}"
echo "Container name: ${containerName}"
echo "Environment: ${environment}"
