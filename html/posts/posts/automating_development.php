<meta written="2016-01-07" slug="automating_development_deployment" name="Automation: Vagrant, Ansible and Selenium" description="Using Vagrant, Ansible, Selenium and Git to automate development and deployment." />

<header>
  <h2>
    Automation with Vagrant, Ansible, Selenium and Git
  </h2>

</header>

<p>
  The big purpose of code, and technology in general, is minimizing effort. Depending on the <a href="http://www.oxforddictionaries.com/us/definition/american_english/convenience">definition</a>, this is the same as maximizing convenience.
</p>

<p>
  Software and technology have become sort of synonymous, but software is much younger. To coders and non-coders alike, it's obvious that software is evolving like crazy. In public, the current phase of this evolution has us sharing, rating and paying for everything online, increasingly with our phones.
</p>

<p>
  In "private", the coders who build this maximally convenient world find ways to make their own work more convenient. They call it <a href="https://en.wikipedia.org/wiki/List_of_build_automation_software">automation</a>. What started with scripts to <a href="https://www.gnu.org/software/make/">make</a> programs from source code has exploded into a dizzying profusion of frameworks for automating all aspects of software development and deployment. If this sounds like hyperbole, or even pessimism, it's probably because that's what I felt when I first contemplated the automation ecosystem.
</p>

<p>
  Recently I wanted to change the OS of the AWS box serving this site, from <b>Amazon Linux</b> to <b>Ubuntu 14.04</b>. I wanted the transition to be as painless as possible. More than anything, I wanted to be sure that once I had configured and tested locally, there would be no surprises deploying the site to production. I haven't worked in DevOps, and my grasp of automation was weak, so I knew I'd have to read first, decide which tools were best for the job, and then learn to put them to use.
</p>

<p>
  I'm here to report that my experience has given me cause for optimism. As young as it is, maybe coding has entered its postmodern phase, minus the bullshit. Code automation is self-regarding in a good way, and it makes our work more fun. It's a good time to be a coder.
</p>

<h3>Works on My Machine</h3>
<p>
  I first developed this site using <b>MAMP</b>. Then I registered my domain, configured an Elastic IP with AWS, set up a <b>LAMP</b> stack on the server and copied the code over using <code>rsync</code>. It didn't seem unsophisticated at the time. There was frustration and debugging of the <a href="http://blog.codinghorror.com/the-works-on-my-machine-certification-program/">WOMM</a> variety, but I figured it was part of the job, and that it might even build character.
</p>

<p>
  This time, however, I wanted to avoid frustration. This is where <b>Vagrant</b> and <b>Ansible</b> come in. Combining them makes WOMM a thing of the past.
</p>

<p>
  Ansible automates configuration. It translates a YAML playbook into shell commands and executes them on remote machines via SSH. One advantage of Ansible over other configuration managers is that you install and run it on your machine. There's no agent on the remote server.
</p>

<p>
  Vagrant makes it easy to spin up and tear down local VMs, and lets you provision them with tools like Ansible. It runs VMs in <b>headless mode</b>, meaning there is no UI, and access is via SSH to a local IP. This is great, because it's exactly how you access production servers in the cloud. Vagrant provides convenience methods for choosing a hostname and mapping your project's root directory on the VM to its local root directory, a la <a href="https://en.wikipedia.org/wiki/SSHFS">SSHFS</a>. Using <a href="https://github.com/cogitatio/vagrant-hostsupdater">vagrant-hostsupdater</a>, your host is automatically added and removed from your hosts file when you start and stop the VM.
</p>

<p>
  So, I built my deploy playbook locally, testing it against the Vagrant VM. I won't go into details, beyond mentioning that I deploy site files by installing Git and cloning my site's Github repo. Once <b>dronfelipe.dev</b> was working as expected, I spun up a new AWS box running Ubuntu 14.04 and <b>configured it with the same playbook</b>. The screenshot below shows the output of this moment of truth. I admit I had my fingers crossed, but there was no need. After all, the AWS machine was probably indistinguishable from the VM running on my computer. After 5 or 10 minutes the playbook finished. I went to the box's public IP and there was my site!
</p>

<div class="image"><a href="img/dronfelipe_ansible.png"><img src="img/thumbs/dronfelipe_ansible.png"></a></div>

<p>
  To finish up, I disassociated my Elastic IP from the old Amazon Linux box and associated it with the new one, which took less than a minute.
</p>

<h3>Automated Testing: Git and Selenium</h3>
<p>
  There are lots of tools and platforms to automate testing, but for my purposes <b>Git</b> is sufficient. The setup is rustic but effective. I have a <code>pre-push</code> hook in my repo which gets copied into <code>.git/hooks</code> on deployment. Every time I try to push code to Github, the hook runs functional tests and rejects commits that break the site. I use <code>pre-push</code> instead of <code>pre-commit</code> to make sure I'm not discouraged from <a href="https://sethrobertson.github.io/GitBestPractices/#commit">committing early and often</a>.
</p>

<p>
  I write functional tests using <b>Selenium</b>. Selenium lets you code test scripts that fire up a browser and direct it from one page to another, clicking on things, entering text, and generally doing anything a user might do. You write tests that make assertions about what the HTML should look like, and how each page should react to various events, instead of having to do this by hand.
</p>

<p>
  Once I push changes to Github, I have an Ansible playbook for pulling them down to the site, which I run from my machine. I know the changes won't break anything because they've been vetted by the functional tests.
</p>

<h3>Big Picture</h3>
<p>
  Automation is good. It lets you focus more on coding and less on "building character". Much of it has come out of the need to coordinate big, diverse teams of developers, but there's no reason you can't use it to make working on your own projects more pleasant.
</p>

<p>
  Git and Github (or analogous version control software and web apps) are prerequisites for automation, and essential to being a happy programmer. They take the fear and stress out of coding. They encourage you to keep everything related to your project in one place, let you share it with anyone, and guarantee that none of it gets lost. In the spirit of these ideas, my site and the code I use to automate it are on Github, in case you want to <a href="https://github.com/kylebebak/dronfelipe">check it out</a>.
</p>
