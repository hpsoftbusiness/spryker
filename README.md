# MyWorld Project
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

## Vagrant Installation
To install the project in Vagrant on your machine, follow [Developer Getting Started Guide](https://documentation.spryker.com/docs/dev-getting-started).

__NOTE: instead of `vagrant up` run `VM_PROJECT=suite SPRYKER_REPOSITORY="git@github.com:spryker-projects/myworld-project.git" vagrant up`__.

For common installation issues, check [Troubleshooting](https://documentation.spryker.com/docs/troubleshooting).

## Docker installation

For detailed installation instructions of Spryker in Docker, see [Getting Started with Docker](https://documentation.spryker.com/docs/getting-started-with-docker).

For troubleshooting of Docker based instanaces, see [Troubleshooting](https://documentation.spryker.com/docs/spryker-in-docker-troubleshooting).

### Prerequisites

For the installation prerequisites, see [Docker Installation Prerequisites](https://documentation.spryker.com/docs/docker-installation-prerequisites).

Recommended system requirements for MacOS:

|Macbook type|vCPU| RAM|
|---|---|---|
|15' | 4 | 6GB |
|13' | 2 | 4GB |

### Installation

Run the commands:
```bash
mkdir myworld && cd myworld
git clone git@github.com:360codelab/spryker-marketplace.git ./
git submodule update --init --force docker
```

### Production-like environment

1. Run the following commands right after cloning the repository:

```bash
docker/sdk boot -s
```

> Please, follow the recommendations in output in order to prepare the environment.

```bash
docker/sdk up
```

2. Git checkout with assets and importing data:

```bash
git checkout your_branch
docker/sdk boot -s
docker/sdk up --assets --data
```

> Optional `up` command arguments:
>
> - `--assets` - build assets
> - `--data` - get new demo data

3. Light git checkout:

```bash
git checkout your_branch
docker/sdk boot -s

docker/sdk up
```

4. Reload all the data:

```bash
docker/sdk clean-data && docker/sdk up && docker/sdk console q:w:s -v -s
```

### Developer environment

1. Run the commands right after cloning the repository:

```bash
docker/sdk boot deploy/dev/deploy.dev.cluster-1.yml
```

> Please, follow the recommendations in output in order to prepare the environment.

```bash
docker/sdk up
```

2. Git checkout:

```bash
git checkout your_branch
docker/sdk boot -s deploy/dev/deploy.dev.cluster-1.yml
docker/sdk up --build --assets --data
```

> Optional `up` command arguments:
>
> - `--build` - update composer, generate transfer objects, etc.
> - `--assets` - build assets
> - `--data` - get new demo data

3. If you get unexpected application behavior or unexpected errors:

    1. Run the command:
    ```bash
    git status
    ```

    2. If there are unnecessary untracked files (red ones), remove them.

    3. Restrart file sync and re-build the codebase:
    ```bash
    docker/sdk trouble
    docker/sdk boot -s deploy/dev/deploy.dev.cluster-1.yml
    docker/sdk up --build --assets
    ```

4. If you do not see the expected demo data on the Storefront:

    1. Check the queue broker and wait until all queues are empty.

    2. If the queue is empty but the issue persists, reload the demo data:
    ```bash
    docker/sdk trouble
    docker/sdk boot -s deploy/dev/deploy.dev.cluster-1.yml
    docker/sdk up --build --assets --data
    ```

### Troubleshooting

**No data on Storefront**

Use the following services to check the status of queues and jobs:
- queue.myworld.local
- scheduler.myworld.local

**Fail whale**

1. Run the command:
```bash
docker/sdk logs
```
2. Add several returns to mark the line you started from.
3. Open the page with the error.
4. Check the logs.

**MacOS and Windows - files synchronization issues in Development mode**

1. Follow sync logs:
```bash
docker/sdk sync logs
```
2. Hard reset:
```bash
docker/sdk trouble && rm -rf vendor && rm -rf src/Generated && docker/sdk sync && docker/sdk up
```

**Errors**

`ERROR: remove spryker_logs: volume is in use - [{container_hash}]`

1. Run the command:
```bash
docker rm -f {container_hash}
```
2. Repeat the failed command.

`Error response from daemon: OCI runtime create failed: .... \\\"no such file or directory\\\"\"": unknown.`

Repeat the failed command.
