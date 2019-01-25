# dronfelipe
**[drohn fe-_lee_-pe]**

**Elastic (unchanging) IP for production server**
`52.10.190.68`

**Document root**
`/var/www/dronfelipe/html`


## Tests
```sh
# if a base site is passed, it must be the last argument, and must have the form shown below
python functional.py -v [s=<base_site>]
# run only one test
python functional.py Functional.<test_function> -v [s=<base_site>]
```


## Deployment
### Development
Need to get off of Apache first, which probably means getting off of PHP.


### Production
I ditched Ansible a long time ago, which means the old deployment process is broken.

Until I get a Docker container set up for this, deployment means copying files over to the server using `scp`...

~~~sh
# make sure "ubuntu" user has write access to `/var/www/dronfelipe`
ssh -i ~/.ssh/id_rsa ubuntu@52.10.190.68
sudo chown -R $USER /var/www/dronfelipe/

# copy files
scp -i ~/.ssh/id_rsa -r html ubuntu@52.10.190.68:/var/www/dronfelipe
~~~


## SSL
For legacy reasons, the site uses NGINX in front of Apache. SSL termination happens at the NGINX layer.

I use Let's Encrypt to generate SSL certificates and modify `dronfelipe-nginx.conf` to use them. Instructions on how to install and use Let's Encrypt [can be found here](https://www.digitalocean.com/community/tutorials/how-to-secure-nginx-with-let-s-encrypt-on-ubuntu-16-04).

This config also conveniently redirects __http__ to __https__.
