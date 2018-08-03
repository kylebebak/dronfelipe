# dronfelipe
**[drohn fe-_lee_-pe]**

**Virtual host for development**
<http://dronfelipe.dev/>

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
To "check" a post which has not been inserted to the `post` table, or to "check" an asset:
<http://dronfelipe.dev/check/post.php/?p=<filename_no_extension\>>
<http://dronfelipe.dev/check/asset.php/?a=<filename_no_extension\>>

## Deployment
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

### Incremental Deployment
```sh
ansible-playbook pull.yml -i "dronfelipe.com," -e "production=True" -u ubuntu
```


## "Continuous" Integration
A `pre-push` Git hook runs functional tests against the code and rejects commits if they fail. If the push succeeds, the changes can be pulled onto the production server using Ansible. A more complete solution involves pushing commits to a `dev` branch. Then, whenever this branch is merged into `master`, a [Github webhook](https://help.github.com/articles/about-webhooks/) instructs the production server to pull the latest commits from `master`.

```sh
# pull from Github to production server
ansible-playbook pull.yml -i "dronfelipe.com," -u ubuntu
```
