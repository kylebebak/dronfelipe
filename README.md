# DRONFELIPE
**[drohn fe-*lee*-pe]**

## elastic (unchanging) IP
`52.10.190.68`

## virtual host for development
<http://dronfelipe.dev/>

## document root
`/var/www/dronfelipe/html`

## tests

## provisioning
```sh
# development
vagrant up
# re-provision development
vagrant reload --provision

# production
ansible-playbook -i <hostfile> main.yml -e production=true
```
