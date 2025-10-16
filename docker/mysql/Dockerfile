FROM mysql:8.0

# Copy init scripts
COPY init/ /docker-entrypoint-initdb.d/

# Set default configuration
ENV MYSQL_ROOT_PASSWORD=root_password
ENV MYSQL_DATABASE=pos_system
ENV MYSQL_USER=pos_user
ENV MYSQL_PASSWORD=pos_password

# Set character set to UTF-8
ADD my.cnf /etc/mysql/conf.d/my.cnf

EXPOSE 3306

CMD ["mysqld"]