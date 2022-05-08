FROM php:cli
WORKDIR /var/www/html
ENV ZOMBOID_SERVER_NAME "pz"
ENV SQL_HOST "host"
ENV SQL_USER "username"
ENV SQL_PASS "pass"
ENV SQL_TABLE "Zombo"

RUN apt-get update && apt-get -y install cron
RUN docker-php-ext-install mysqli

# Create the log file to be able to run tail
RUN touch /var/log/cron.log
RUN echo "*/5 * * * * root BASH_ENV=/etc/profile php /var/www/html/reportServerData.php >> /var/log/cron.log 2>&1" >> /etc/crontab

COPY . .
 
# Run the command on container startup

CMD env >> /etc/environment && cron && tail -f /var/log/cron.log


