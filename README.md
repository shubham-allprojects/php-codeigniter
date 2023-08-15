# Spider Application setup ( Codeigniter Framework )

## Pre-requisite:
- XAMPP (with PHP 8)

## Installation Steps
- Clone repo in server root directory, using one of the below commands:
`git clone git@bitbucket.org:nortek-control/nxgcpub-spider-web.git`
`git clone https://<username>@bitbucket.org/nortek-control/nxgcpub-spider-web.git`
- Change branch: `git checkout php8-security-migration`
- Copy `env.example` to `.env` 
- Update `.env` file with the baseURL if it is different
- Run `composer install` command to download dependencies.
- Create a new sub-directory named `database` under `writable` directory
- Copy sqlite3 db files `api.db`, `Network.db`, `Spider.db`, `SpiderLog.db` to the `database` directory