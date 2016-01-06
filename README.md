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
python main.py -v
# run only one test
python main.py DronFelipe.<test_function> -v
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
