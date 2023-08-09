# Spider Application setup ( Codeigniter Framework )

## Installation Steps

- Run `git clone git@bitbucket.org:nortek-control/nxgcpub-spider-web.git` command to clone the repository.
- copy `env.example` to `.env` and tailor for your app, specifically the baseURL. 
- Run `composer install` command to download dependencies.
- Create new `database` folder in writable folder & add sqlite3 db files eg. api.db, Network.db, Spider.db, SpiderLog.db

## GIT Branches
- create a new branch from `php8-security-migration` base branch for each JIRA ticket.
- make sure to sync your local branch with remote branch