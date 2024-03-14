# Void

## A simple file server written with PHP & Nginx, that can build and push Docker images to DockerHub

This microservice project is a **Part 3** of **'Web-Based Project Management System'**. 
 * [Part 1 'WEB Server'](https://github.com/YushchenkoAndrew/mortis-grimreaper)
 * [Part 2 'API Server'](https://github.com/YushchenkoAndrew/grape)
 * [Part 3 'File Server'](https://github.com/YushchenkoAndrew/void)
 * [Part 4 'bot'](https://github.com/YushchenkoAndrew/botodachi)

![System](/img/System.jpg)

 So I guess you wondering, why I called it *'void'*. Ehh, because it's a File Server and you know, it's kinda ironic in a way ..., right ?

Anyway, I build this project only for *2 reasons*:
* Fist, back in the days, I was needed a File Server which is support CRUD API, and I didn't wanted to recompile Nginx to include some third part libs
* Second, I wanted to use a bit complex business logic in some request (for example: building a Docker image)


This project is quite simple then other services of **'Web-Based Project Management System'**. The main logic of it, is to be able to upload/delete files to NFS + to be able to build Docker image and push it to DockerHub.

ROUTES:
* **[HEAD]** /void -- ping
* **[GET]** /void/{path} -- Download Files & check file tree with browser 
* **[POST]** /void?path=/test **header** { Authorization: `Basic base64(pass)` }  **body** { file: [file] } -- Upload a file (directory will be created automatically)
* **[DELETE]** /void?path=/test **header** { Authorization: `Basic base64(pass)` } -- Delete a file/directory
* **[GET]** /void/docker  **header** { Authorization: `Basic base64(pass)` } -- Get Docker images from API
* **[POST]** /void/docker?path=/test&t={docker_hub_username}/{repo_name}:{repo_version} **header** { Authorization: `Basic base64(pass)` } -- Build a docker image in dir /test
* **[POST]** /void/docker/push?t={docker_hub_username}/{repo_name}:{repo_version} **header** { Authorization: `Basic base64(pass)`, X-Registry-Auth: `base64({ username: {docker_hub_username}, password: {docker_hub_pass}, email: {docker_hub_email}, serveraddress: {ip} })` } -- Push Docker image to DockerHub
* **[DELETE]** /void/docker **header** { Authorization: `Basic base64(pass)` } -- Prune the images


## Diagram
![Diagram](/img/Void.jpg)

## How to use this project

1. Clone this repo
2. Create htpasswd file
```
htpasswd -c ./.htpasswd {your_user_name}
```
3. Create htpasswd file
4. Build a docker image for this project
```
build docker build . -t void
```
5. Start a docker image
```
docker run --name void -d -p 8003:8003 void
```

Now you can just open a browser and visit http://127.0.0.1:8003/void/

## Found a bug ?
Found something strange or just want to improve this project, just send a PR.

## Features
- [x] - CRUD API
  - [x] - GET file
  - [x] - POST file
  - [x] - DELETE file
- [x] - Docker API
  - [x] - Get available Docker Images
  - [x] - Build a Docker Images
  - [x] - Push the Docker Images to DockerHub
  - [x] - Prune Docker Images
- [ ] - Tests
  - [x] - CRUD API
  - [ ] - Docker API