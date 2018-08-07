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
I ditched Ansible for Docker, but haven't set up Docker containers yet. Just copy files over to the server using `scp`...


```sh
# pull from Github to production server
ansible-playbook pull.yml -i "dronfelipe.com," -u ubuntu
```
