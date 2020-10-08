# WEC Containers Task

## Setup and How to use
* Clone this repository to your machine.
* Make sure you have installed [docker](https://docs.docker.com/get-docker/) and [docker compose](https://docs.docker.com/compose/install/)
* Open the terminal and navigate to the folder where you have docker-compose.yml file.
* Run 
`` sudo docker-compose up `` 
Note that this command automatically builds all the images and starts all the containers by following the instructions given in docker-compose.yml.
* If you get any error something like POSTGRESQL ADDRESS 5432 ALREADY IN USE. Run ``sudo systemctl stop postgresql``
* Open up the browser and test the app using http://localhost:8080/ .
* If you want to test all the containers use the following URLs
  * Nodejs express app - http://localhost:3000/
  * Flask app - http://localhost:5000/


## Explainations/My understandings

### What are containers?
A container is a piece of software unit that packages up all the code, dependencies, environment etc so that the application can run in any machine.
This helps in spinning up the application easily in any machine without having to deal with installing the dependencies. This also helps new developers if they are joining the team who are working on a software with lot of dependencies by making their the process of setting the environment less tedious.

### Docker

Hypervisor is a hardware which creates and manages guest OSs which are created on Host OS. Many virtual machines can be created with the help of hypervisor and the user application(on guest OS) has to pass through Guest OS and then Hypervisor inorder to access the hardware. Hypervisor manages all these things. VMs runs a complete operating system including the kernel, thus requiring more system resources (CPU, memory, and storage).

Docker is an open source technology to containerize softwares. Dockers are lightweight because in docker we don't use hypervisors unlike Virtual machines. Dockers kindof shares the OS files like kernel of the host OS. This helps in reducing system resources. All containers share the same kernel(host OS) in docker.
This means we can run more containers on a given hardware than if you are using virtual machines.
Docker uses the same kernel of host OS whereas Virtual machines creates a whole new operating system on top of host OS.

#### Some of it's advantages are
* Docker is faster than VMs.
* It encourages microservices architecture.
* User applications running on docker has faster hardware access than those running on VMs.
* Faster to setup and start the application.

## Dockerfile
Dockerfile is a text document which specifies all the things to create an image. The Dockerfile is essentially the build instructions to build the image.
Also anyone who has this Dockerfile can build the image instead of downloading the copy of docker image itself.
Note that Dockerfile has to be named as Dockerfile itself without any extensions and also D should be capitalized. If you write Dockerfile in any other way, docker will throw an error.

### Some of the commands used in Dockerfile

```
FROM <base_image>:tag
```
This tells the docker to use ``<base_image>:tag`` as the base image and build the image on top of this base image.


```
WORKDIR /pahta/pathb/...
```
The WORKDIR command is used to define the working directory of a Docker container at any given time. Any RUN, CMD, ADD, COPY, or ENTRYPOINT command will be executed in the specified working directory. WORKDIR can be reused to set a new working directory at any stage of the Dockerfile. The path of the new working directory must be given relative to the current working directory.


```
COPY src_path dest_path
```
The COPY command tells the docker to copy the file/files in the src_path to the dest_path. src_path is in the host and dest_path is created in the image.


``` 
RUN command
```
The RUN command is used to run bash commands. The result of which happens in the image.


```
CMD["X", "Y", "", "", ...]
```
To use commands like ``python app.py`` we can use CMD ["python", "app.py"]


```
EXPOSE <port> 
```
The EXPOSE instruction informs Docker that the container listens on the specified network ports at runtime. You can specify whether the port listens on TCP or UDP, and the default is TCP if the protocol is not specified.


``` 
ENV <key>=<value>
```
The ENV instruction sets the environment variable <key> to the value <value>. 


  ```
      ENTRYPOINT ["python"]
      CMD ["app.py"]
  ```
  This is equivalent to running python app.py
  
  
  ## docker-compose
  Imagine you have tens and hundreds of microservices. So to start them we have to build them and start each of them . This is a tedious task because you have to keep checking which all images you have build already and what all containers have you started. This is the reason we use docker-compose command to make all these things simpler.
  
  First we need to make docker-compose.yml file. This contains all the things which we want our image to have.
  
  ``` 
  version: 'x' 
  ``` 
  This is the version of the docker-compose.yml file format. We are using version 3
  
  
  ``` 
  services:
  ``` 
  The components/microservices. For this task there are 4 services: db, apparel, prices, php-app
  
  
  ``` 
  build: path
  ``` 
  To build this service use this path where there is Dockerfile for the service. I have written Dockerfiles for all the services.  We can also build the image from using the base image and then specifying all the things mentioned in the Dockerfile without creating Dockerfile
  
  
  ```
  ports:
    - '8080:80'
   ```
   This maps the port 80 which is exposed by the container to 3000 of the host OS.
   
  
  ``` 
  depends_on: service1
  ```
  (written for service2) To tell that the service2 depends on servicee 1.
   
   
   ```
    volumes:
      pathA:pathB
   ```
   Once the container is stopped all the data which we wanted are lost as the data was stored in the container. So inorder to use this data, we persist the data by specifying the pathA(relative to docker-compose.yml) of the host where we want to store the data so that we can use them even after stopping the container.
   
   
## My approach
1. I created 4 services as per the task requirement by building four Dockerfiles for all the services.
2. First I ensured that the app works fine without dockerizing.
3. I came to know that http:localhost:port should be replaced with name of the service mentioned in the docker-compose.yml to access or request data from one container to another container. So to access apparel from prices we need to use http://apparel:3000 where the port is the one we exposed for apparel container in the dockerfile and not the one it is mapped to for HOST OS. Because docker creates network among the containers by default and we can use http://service_to_access:port_it_is_exposed_in_docker if we want to access other container from any other container.
4. So I mapped all the ports in docker-compose.yml to the host and also named services accordingly so that I can use later.
5. First the data has to be created in db container. The docker helps us put all the seed data if we have any in the db container. I did this by using ``COPY init.sql /docker-entrypoint-initdb.d/ `` Docker automatically executes all the sql in the init.sql when the container is created. Also we need to make sure that this init.sql is copied to the path ``/docker-entrypoint-initdb.d/``
6. Also the default user for postgresql is postgres . I also mentioned the default db by specifying in the Dockerfile of db folder. Here I named the database as tempdb. Like I also specified the password for the postgres user. The default port for postgresql is 5432 . I mapped this to 5432(same for host OS).
7. After this I added few things to the configuration of the pool in apparel/server.js by adding the database to connect to, user, password, port through which we connect. Also host is db(name of the database service mentioned in docker-compose.yml so that we can connect(see point 3)).
8. The apparel service returns the data in the apparel table when made a get request to it.
9. Basically how this application works is, php is responsble for showing the end result. This php service first requests the flask(prices) service for the data, prices in turn requests apparel service. apparel then requests the postgres database and the data is returned. Then apparel sends this data to prices as an object Prices then puts prices(cost) to all these data and sends it as json to php . PHP then uses HTML to display to the user.
10. Likewise point 7 I made changes to flask service by adding the request path and same for php also.
11. Also using docker layer caching I ensured in the node app there are less builds required(In case we just update the code without installing any new npm package, we can just place npm install command in Dockerfile before copying the source code files so that we can use cached image till that step where we copy code files. I did same with flask service by placing pip install requirements.txt above COPY source files).

## Few important commands to list
```
sudo docker image ls
```
Lists all the images built.


```
sudo docker ps -a
```
Shows all the containers with their status, container ID, used image etc.


```
sudo docker image rm <image_id>
```
Deletes the image with given ID.


```
sudo docker rm <container_id>
```
Deletes the contaienr with the given ID.


```
sudo docker built -t <name>:tag -p x:y -d <Dockerfile path>
```
Builds image with name as ``<name>:tag`` (default tag is latest), -d is to run the container in background(detached mode) -p is to specify the port mapping.


```
sudo docker-compose up
```
Builds all the images(if not built) by checking docker-compose.yml and starts all the container.
