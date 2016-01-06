# DRONFELIPE
**[drohn fe-*lee*-pe]**

## elastic (unchanging) IP
`52.10.190.68`

## virtual host for development
<http://dronfelipe.dev/>

## document root
`/var/www/dronfelipe/html`

## tests
```sh
# if a base site is passed, it must be the last argument, and must have the form shown below
python functional.py -v [s=<base_site>]
# run only one test
python functional.py Functional.<test_function> -v [s=<base_site>]
```


## provisioning
### development
```sh
vagrant up
# re-provision
vagrant reload --provision
```

### production
```sh
# without inventory file
ansible-playbook main.yml -i "dronfelipe.com," -e "production=True" -u ubuntu
```
