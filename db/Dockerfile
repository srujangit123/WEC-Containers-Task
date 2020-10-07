FROM postgres:alpine

ENV POSTGRES_USER postgres
ENV POSTGRES_DB tempdb
# ENV POSTGRES_HOST_AUTH_METHOD trust
ENV POSTGRES_PASSWORD password
EXPOSE 5432

COPY init.sql /docker-entrypoint-initdb.d/
