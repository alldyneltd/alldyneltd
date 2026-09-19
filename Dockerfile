FROM php:8.2-apache

# Instala extensões necessárias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Copia o projeto para o diretório público do Apache
COPY . /var/www/html/

# Permissões básicas
RUN chown -R www-data:www-data /var/www/html

# Render utiliza a porta definida pela variável PORT
ENV PORT=10000

# Configura o Apache para escutar na porta do Render
RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf && \
    sed -i 's/:80>/:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD ["apache2-foreground"]