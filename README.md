# dronfelipe
**[drohn fe-*lee*-pe]**

## Elastic (unchanging) IP
`52.10.190.68`

## Virtual host for development
<http://dronfelipe.dev/>

## Document root
`/var/www/dronfelipe/html`


## Tests
```sh
# if a base site is passed, it must be the last argument, and must have the form shown below
python functional.py -v [s=<base_site>]
# run only one test
python functional.py Functional.<test_function> -v [s=<base_site>]
```


## Provisioning
### Development
```sh
vagrant up
# re-provision
vagrant reload --provision
```

### Production
```sh
# without inventory file
ansible-playbook main.yml -i "dronfelipe.com," -e "production=True" -u ubuntu
```


## "Continuous" Integration
A `pre-push` git hook runs functional tests against the code and rejects commits if tests fail. If the push succeeds, the changes can be pulled onto the production server using Ansible. A more complete solution involves pushing commits to a `dev` branch. Then, whenever this branch is merged into `master`, a [Github webhook](https://help.github.com/articles/about-webhooks/) could instruct the production server to pull the latest commits from `master`.

```sh
# pull from Github to production server
ansible-playbook update.yml -i "dronfelipe.com," -u ubuntu
# insert post into DB on production server
ansible-playbook insert_post.yml -i "dronfelipe.com," -e "post=<filename>" -u ubuntu
ansible-playbook delete_post.yml -i "dronfelipe.com," -e "post=<slug>" -u ubuntu
```

