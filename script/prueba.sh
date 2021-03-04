#!/bin/bash
#copiar archivo modificado
sudo cp ./00-installer-config.yaml /etc/netplan/
	echo "configuracion de ip aplicada"
#aplicar cambios ip
sudo netplan apply
echo "Actualizacion del servidor"
#actualizar repositorio
sudo apt update

   echo "Insatalamos apache2"
#instalamos apache2
sudo apt install -y apache2

#Cambiar paginas de errores modicando 000-default.conf /etc/apache2/sites-avaliables 
# /var/www/html aqui podemos meter nuestra pagina de error Errordocument

#instalar mod_php para leer archivos php
	echo "Instalamos mod_php"

sudo apt install -y php libapache2-mod-php
cp ./000-default.conf /etc/apache2/sites-availables/000-default.conf

sudo service apache2 restart

#Instalamos mysql
	echo "Instalamos mysql"

sudo apt install -y mysql-server

#Restablecer contraseña para root

	echo "Inciar servicio"

sudo service mysql start

	echo "Restablecer contraseña"

sudo mysql -u root --password="" -e "update mysql.user set authentication_string=password(''), plugin='mysql_native_password' where user='root';"
sudo mysql -u root --password="" -e "flush privileges;"
#tenemos que cambiar nombre de nuestra base de datos y cambiarla para importarla
#sudo mysql -u root --password="" -e "create database aergibideDB;"

		echo "importar base de datos"


#Damos permisos
sudo chmod 777 ./aergibide.sql
#importamos la base de datos
sudo mysql -u root --password=""  < ./aergibide.sql

sudo service mysql restart

#Instalar FTP
 		echo "Instalar FTP"

sudo apt install -y vsftpd
		echo "remplazararchivo modicifaco"
sudo chmod 777 ./vsftpd.conf

sudo cp ./vsftpd.conf /etc/vsftpd.conf

sudo service vsftpd restart

		echo "instalar ssh"

sudo apt install -y openssh-server

#crear ususario y contraseña


sudo useradd -m -p $(openssl passwd -1 12345) team 
	echo "usuario creado"

sudo cp ./sshd_config /etc/ssh/sshd_config
sudo service ssh restart


#copiar archivos de la pagina




	


